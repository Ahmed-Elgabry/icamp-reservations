<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{Customer,Registrationform};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RegistrationformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $registration_forms = Registrationform::with('service')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function($qq) use ($q){
                    $qq->orWhere('id', (int)$q)
                       ->orWhere('mobile_phone', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%")
                       ->orWhere('first_name', 'like', "%{$q}%")
                       ->orWhere('last_name', 'like', "%{$q}%")
                       ->orWhere('request_code', 'like', "%{$q}%")
                       ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$q}%"]);

                });
            })
            ->latest()
            ->get();

        return view('dashboard.registration_forms.index', compact('registration_forms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('dashboard.registration_forms.edit' , ['registration_form' => \App\Models\Registrationform::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
    */
    public function update(Request $request, $id)
    {
        $rf = Registrationform::findOrFail($id);
        $validated = $request->validate([
            'booking_date' => 'required|date',
            'time_slot'    => 'required|string|max:50',
            'persons'      => 'required|integer|min:1',
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'mobile_phone' => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:100',
            'notes'        => 'nullable|string|max:2000',
        ]);
        DB::transaction(function() use($validated, $rf){
            $rf->update($validated);
        });
        return back()->with('success', __('dashboard.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rf = \App\Models\Registrationform::findOrFail($id);
        $rf->delete();
        return redirect()->back()->with(['success' => __('dashboard.deleted_ok')]);
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $items = \App\Models\Registrationform::with('service')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function($qq) use ($q){
                    $qq->orWhere('id', (int)$q)
                    ->orWhere('request_code', 'like', "%{$q}%")
                    ->orWhere('mobile_phone', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$q}%"]);
                });
            })
            ->latest()->limit(20)->get();

        $results = $items->map(function($rf){
            $name = $rf->full_name;
            $label = sprintf('#%s | %s | %s | %s',
                $rf->request_code ?: $rf->id,
                $rf->mobile_phone ?: '—',
                $name ?: '—',
                $rf->email ?: '—'
            );
            return ['id' => $rf->id, 'text' => $label];
        });

        return response()->json(['results' => $results]);
    }

    public function fetch($id)
    {
        $rf = Registrationform::with('service')->findOrFail($id);
        $cust = Customer::query()
                        ->when($rf->mobile_phone, fn ($q) => $q->where('phone', $rf->mobile_phone))
                        ->when($rf->email, fn ($q) => $q->orWhere('email', $rf->email))
                        ->first();
        if (!$cust) {
            $cust = Customer::create([
                'name'  => $rf->full_name ?: $rf->full_name,
                'phone' => $rf->mobile_phone,
                'email' => $rf->email,
            ]);
        }
        $slotToTimes = [
            '4-12' => ['16:00', '00:00'],
            '5-1'  => ['17:00', '01:00'],
        ];
        [$from, $to] = [null, null];
        if ($rf->time_slot === 'other') {
            $from = $rf->checkin_time;
            $to   = $rf->checkout_time;
        } elseif (isset($slotToTimes[$rf->time_slot])) {
            [$from, $to] = $slotToTimes[$rf->time_slot];
        }

        return response()->json([
            'rf_id'         => $rf->id,
            'people_count'  => (int) $rf->persons,
            'service_ids'   => [$rf->service_id],
            'date'          => optional($rf->booking_date)->format('Y-m-d'),
            'time_from'     => $from->format('H:i'),
            'time_to'       => $to->format('H:i'),
            'notes'         => (string) $rf->notes,
            'prefill_mobile'=> (string) $rf->mobile_phone,
            'prefill_email' => (string) $rf->email,
            'customer' => [
                'id'    => $cust->id,
                'name'  => $cust->name,
                'phone' => $cust->phone,
                'email' => $cust->email,
            ]
        ]);
    }

    public function customer(Request $request)
    {
        $reg = Registrationform::findOrFail($request->id);
        if (!$reg)
            return response()->json(['results' => []]);

        $cust = Customer::query()
            ->where('phone', 'like', $reg->mobile_phone)
            ->orWhere('email', 'like', $reg->email)
            ->first();
        if (!$cust){
            $cust = customer::create([
                'name'  => $reg->full_name ?: $reg->full_name,
                'phone' => $reg->mobile_phone,
                'email' => $reg->email,
            ]);
        }

        return response()->json(['customer' => $cust]);
    }
}
