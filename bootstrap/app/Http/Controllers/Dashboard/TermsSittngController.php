<?php
namespace App\Http\Controllers\Dashboard;

use Mpdf\Mpdf;
use App\Models\Order;
use App\Models\Payment;
use App\Models\TermsSittng;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TermsSittngRequest;
use TCPDF;


class TermsSittngController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', TermsSittng::class);
        $termsSittings = TermsSittng::first();
        \Log::info("1");
        \Log::info($termsSittings);
        return view('dashboard.TermsSittngs.create', compact('termsSittings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id = null)
    {
        $this->authorize('create', TermsSittng::class);

        // Load specific record if ID provided, otherwise the first (singleton behavior)
        $termsSittings = $id ? TermsSittng::find($id) : TermsSittng::first();
        \Log::info('3');
        // عرض صفحة الإنشاء مع البيانات الحالية إن وجدت
        return view('dashboard.TermsSittngs.create', compact('termsSittings'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TermsSittngRequest $request)
    {
        $this->authorize('create', TermsSittng::class);
        $data = $request->validated();

        // For singleton settings: update the first row if exists, otherwise create
        $terms = TermsSittng::first();
        if ($terms) {
            $terms->update($data);
        } else {
            $terms = TermsSittng::create($data);
        }
        \Log::info('2');
        return redirect()->route('terms_sittngs.create')
            ->with('success', 'تم تحديث الإعدادات بنجاح.');
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $termsSittings = TermsSittng::findOrFail($id);
        $this->authorize('view', $termsSittings);

        return view('dashboard.TermsSittngs.create', compact('termsSittings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $termsSittings = TermsSittng::findOrFail($id);
        $this->authorize('update', $termsSittings);

        return view('dashboard.TermsSittngs.create', compact('termsSittings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TermsSittngRequest $request, $id)
    {
        $termsSittings = TermsSittng::findOrFail($id);
        $this->authorize('update', $termsSittings);
        $data = $request->validated();
        \Log::info('4');
        $termsSittings->update($data);
        return redirect()->route('terms_sittngs.create')
            ->with('success', 'تم تحديث الإعدادات بنجاح.');
    }

    public function generatePDF(Request $request)
    {
        // إعداد البيانات للتصدير
        $signatureDrawn = $request->input('signature_drawn'); // بيانات التوقيع المرسوم
        $signatureText = $request->input('signature_text'); // بيانات التوقيع المكتوب

        // إنشاء كائن TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // إضافة نص إلى الـ PDF
        $pdf->SetFont('Cairo', '', 12);
        $pdf->Cell(0, 10, 'Payment Receipt', 0, 1, 'C');

        // إضافة التوقيع المكتوب
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Signature: ' . $signatureText, 0, 1);

        // إضافة التوقيع المرسوم
        if ($signatureDrawn) {
            $img = 'data:image/png;base64,' . $signatureDrawn; // تحويل البيانات إلى صورة
            $pdf->Image($img, 15, 50, 100, 50, 'PNG'); // ضبط الموضع والحجم
        }

        // حفظ الـ PDF
        $pdf->Output('receipt.pdf', 'D');
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $termsSittings = TermsSittng::findOrFail($id);
        $this->authorize('delete', $termsSittings);

        $termsSittings->delete();

        return redirect()->route('terms_sittngs.create')
            ->with('success', 'Terms setting deleted successfully.');
    }
}
