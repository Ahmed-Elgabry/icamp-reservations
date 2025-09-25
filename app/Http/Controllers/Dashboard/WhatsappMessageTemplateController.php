<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\WhatsappMessageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WhatsappMessageTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = WhatsappMessageTemplate::query();

        // Type filter only
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $templates = $query->orderBy('type')->orderBy('created_at', 'desc')->paginate(10);

        // Preserve filters in pagination links
        $templates->appends($request->query());

        return view('dashboard.whatsapp_templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = WhatsappMessageTemplate::getTypes();
        return view('dashboard.whatsapp_templates.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:show_price,invoice,receipt,reservation_data,evaluation,payment_link_created,payment_link_resend,booking_reminder,booking_ending_reminder,manual_template',
            'message_ar' => 'required|string',
            'message_en' => 'required|string',
            'is_active' => 'nullable|in:on,1,true',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            WhatsappMessageTemplate::create([
                'name' => $request->name,
                'type' => $request->type,
                'message_ar' => $request->message_ar,
                'message_en' => $request->message_en,
                'is_active' => $request->has('is_active'),
                'description' => $request->description,
            ]);

            return redirect()->route('whatsapp-templates.index')
                ->with('success', __('dashboard.template_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('dashboard.something_went_wrong'))
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $template = WhatsappMessageTemplate::findOrFail($id);
        return view('dashboard.whatsapp_templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = WhatsappMessageTemplate::findOrFail($id);
        $types = WhatsappMessageTemplate::getTypes();
        return view('dashboard.whatsapp_templates.edit', compact('template', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $template = WhatsappMessageTemplate::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:show_price,invoice,receipt,reservation_data,evaluation,payment_link_created,payment_link_resend,booking_reminder,booking_ending_reminder,manual_template',
            'message_ar' => 'required|string',
            'message_en' => 'required|string',
            'is_active' => 'nullable|in:on,1,true',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $template->update([
                'name' => $request->name,
                'type' => $request->type,
                'message_ar' => $request->message_ar,
                'message_en' => $request->message_en,
                'is_active' => $request->has('is_active'),
                'description' => $request->description,
            ]);

            return redirect()->route('whatsapp-templates.index')
                ->with('success', __('dashboard.template_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('dashboard.something_went_wrong'))
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $template = WhatsappMessageTemplate::findOrFail($id);
            $template->delete();

            return redirect()->route('whatsapp-templates.index')
                ->with('success', __('dashboard.template_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('dashboard.something_went_wrong'));
        }
    }

    /**
     * Toggle template active status
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus($id)
    {
        try {
            $template = WhatsappMessageTemplate::findOrFail($id);
            $template->update(['is_active' => !$template->is_active]);

            $status = $template->is_active ? __('dashboard.activated') : __('dashboard.deactivated');
            return response()->json([
                'success' => true,
                'message' => __('dashboard.template_status_updated', ['status' => $status]),
                'is_active' => $template->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('dashboard.something_went_wrong')
            ], 500);
        }
    }
}
