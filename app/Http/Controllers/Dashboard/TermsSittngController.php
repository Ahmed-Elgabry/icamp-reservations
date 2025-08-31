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
        $termsSittngs = TermsSittng::all();
        return view('dashboard.TermsSittngs.create', compact('termsSittngs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // البحث عن السجل الموجود باستخدام user_id أو أي شرط آخر
        $termsSittng = TermsSittng::query()->first();

        // عرض صفحة الإنشاء مع البيانات الحالية إن وجدت
        return view('dashboard.TermsSittngs.create', compact('termsSittng'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TermsSittngRequest $request)
    {
        $data = $request->all();

        // معالجة رفع الـ Logo
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public'); // حفظ الصورة في مجلد public/logos
        }

        // معالجة رفع الـ Commercial License
        if ($request->hasFile('commercial_license')) {
            $data['commercial_license'] = $request->file('commercial_license')->store('licenses', 'public'); // حفظ الصورة في مجلد public/licenses
        }

        // تخزين البيانات في قاعدة البيانات
        TermsSittng::create($data);

        return redirect()->route('terms_sittngs.create')->with('success', 'Settings saved successfully');
    }



    /**
     * Display the specified resource.
     */
    public function show(TermsSittng $termsSittng)
    {
        return view('dashboard.TermsSittngs.create', compact('termsSittng'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TermsSittng $termsSittng)
    {
        return view('dashboard.TermsSittngs.create', compact('termsSittng'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TermsSittngRequest $request)
    {
        $validatedData = $request->validated();
        // // معالجة رفع الصور
        // if ($request->hasFile('logo')) {
        //     $logoFile = $request->file('logo');
        //     $logoFilename = time() . '_logo.' . $logoFile->getClientOriginalExtension();
        //     $logoPath = $logoFile->storeAs('logos', $logoFilename, 'public');
        //     $validatedData['logo'] = $logoPath;
        // }
        
         TermsSittng::first()->update($validatedData);
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
        $termsSittng->delete();

        return redirect()->route('terms_sittngs.create')
            ->with('success', 'Terms setting deleted successfully.');
    }
}
