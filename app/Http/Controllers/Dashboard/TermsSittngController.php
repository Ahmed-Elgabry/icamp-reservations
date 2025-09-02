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

        $termsSittngs = TermsSittng::all();
        return view('dashboard.TermsSittngs.create', compact('termsSittngs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $this->authorize('create', TermsSittng::class);

        $termsSittng = TermsSittng::query()->first();

        // عرض صفحة الإنشاء مع البيانات الحالية إن وجدت
        return view('dashboard.TermsSittngs.create', compact('termsSittng'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TermsSittngRequest $request)
    {
         $this->authorize('create', TermsSittng::class);


         $termsSittng = TermsSittng::updateOrCreate([
            "commercial_license_ar"=>$request->input('commercial_license_ar'),
            "commercial_license_en"=>$request->input('commercial_license_en')
         ]);



        return redirect()->route('terms_sittngs.create')->with('success', 'Settings saved successfully');
    }



    /**
     * Display the specified resource.
     */
    public function show(TermsSittng $termsSittng)
    {
         $this->authorize('view', $termsSittng);

        return view('dashboard.TermsSittngs.create', compact('termsSittng'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TermsSittng $termsSittng)
    {
         $this->authorize('update', $termsSittng);

        return view('dashboard.TermsSittngs.create', compact('termsSittng'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TermsSittngRequest $request)
    {
        $termsSittng = TermsSittng::first();
         $this->authorize('update', $termsSittng);

        $validatedData = $request->validated();
        $termsSittng->update($validatedData);

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

    public function destroy(TermsSittng $termsSittng)
    {
         $this->authorize('delete', $termsSittng);

        $termsSittng->delete();

        return redirect()->route('terms_sittngs.create')
            ->with('success', 'Terms setting deleted successfully.');
    }
}
