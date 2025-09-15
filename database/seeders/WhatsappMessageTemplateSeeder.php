<?php

namespace Database\Seeders;

use App\Models\WhatsappMessageTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhatsappMessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = [
            [
                'name' => 'عرض السعر',
                'type' => 'عرض السعر',
                'message_ar' => '🌟مرحًبا [اسم العميل]،
سعدنا بتواصلك معنا🌟
📌مرفق لكم ملف PDF يحتوي على عرض سعر حجز المخيم بالتفاصيل.
بانتظار تأكيدكم على الموعد المناسب لإتمام الحجز، ولا تردد بالتواصل معنا لأي استفسار.
مع تحيات FUNCAMP',
                'message_en' => '👋Hello [Customer Name],
We\'re glad to hear from you. 🌟
📌 Attached is a PDF file containing a detailed camp reservation quote.
We await your confirmation of a suitable date to complete your reservation. Please feel free to contact us for any inquiries.
Regards, FUNCAMP',
                'is_active' => true,
                'description' => 'Template for sending price quotes to customers'
            ],
            [
                'name' => 'فاتورة',
                'type' => 'فاتورة',
                'message_ar' => '🌟مرحًبا [اسم العميل]،
سعداء بحجزكم معنا✨
📌 مرفق لكم فاتورة الحجز الخاصة بالمخيم.
بانتظاركم لتجربة ممتعة ولا تُنسى مع 🌌🏕️ Funcamp
مع تحيات FUNCAMP',
                'message_en' => '👋Hello [Customer Name],
We are delighted to have you book with us✨
📌Attached is your camp reservation invoice.
We look forward to an enjoyable and unforgettable experience with Funcamp🌌🏕️
With compliments from FUNCAMP',
                'is_active' => true,
                'description' => 'Template for sending invoices to customers'
            ],
            [
                'name' => 'إيصال القبض',
                'type' => 'إيصال القبض',
                'message_ar' => '🌟مرحًبا [اسم العميل]،
مرفق لكم إيصال قبض خاص بحجز المخيم.
لطفاً للاطلاع،،
شاكرين لكم تعاونكم🌟
مع تحيات FUNCAMP',
                'message_en' => '👋Hello [Customer Name],
Attached is the receipt for your camp reservation.
Please review it.
Thank you for your cooperation🌟.
Regards, FUNCAMP',
                'is_active' => true,
                'description' => 'Template for sending receipts to customers'
            ],
            [
                'name' => 'بيانات الحجز',
                'type' => 'بيانات الحجز',
                'message_ar' => '🌟مرحًبا [اسم العميل]،
مرفق لكم بيانات حجز المخيم
لطفاً للاطلاع.،،
شاكرين لكم تعاونكم🌟
مع تحيات FUNCAMP',
                'message_en' => '👋Hello [Customer Name],
Attached is the camp reservation information.
Please review.
Thank you for your cooperation🌟.
Best regards, FUNCAMP',
                'is_active' => true,
                'description' => 'Template for sending reservation data to customers'
            ],
            [
                'name' => 'التقييم',
                'type' => 'التقييم',
                'message_ar' => '🌟أهلاً [اسم العميل]،
سعدنا بزيارتكم وتجربتكم معنا✨️🏕️
يسعدنا سماع رأيكم وملاحظاتكم من خلال هذا الرابط: [🔗 رابط التقييم]
رأيكم يهمنا لنقدم لكم دائماً 💛 الأفضل',
                'message_en' => '👋Hello [Customer Name],
We\'re glad you enjoyed your visit and experience with us✨🏕️
We\'d love to hear your feedback and comments through this link: [🔗 Evaluation Link]
Your opinion matters to us so we can always provide you with the best💛',
                'is_active' => true,
                'description' => 'Template for sending evaluation surveys to customers'
            ]
        ];

        foreach ($templates as $template) {
            WhatsappMessageTemplate::create($template);
        }
    }
}
