<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WhatsappMessageTemplate;

class PaymentReminderTemplateSeeder extends Seeder
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
                'name' => 'إنشاء رابط دفع الكتروني',
                'type' => WhatsappMessageTemplate::TYPE_PAYMENT_LINK_CREATED,
                'message_ar' => '<p>🌟أهلاً [اسم العميل]،<br>تم إنشاء رابط الدفع الإلكتروني الخاص بحجزكم.<br>يمكنكم إتمام عملية السداد عبر الرابط التالي: [🔗 رابط الدفع]<br>شاكرين لكم تعاونكم ونتمنى لكم تجربة مميزة مع ✨️🏕️ Funcamp</p>',
                'message_en' => '<p>👋 Hello [Customer Name],<br>The online payment link for your reservation has been created.<br>You can complete the payment process via the following link: [🔗 Payment Link]<br>Thank you for your cooperation and we hope you have a great experience with Funcamp 🏕️✨</p>',
                'is_active' => true,
                'description' => 'إنشاء رابط دفع الكتروني'
            ],
            [
                'name' => 'إعادة إرسال رابط الدفع الإلكتروني',
                'type' => WhatsappMessageTemplate::TYPE_PAYMENT_LINK_RESEND,
                'message_ar' => '<p>🌟أهلاً [اسم العميل]،<br>نرسل لكم مجدداً رابط الدفع الإلكتروني الخاص بحجزكم للتسهيل عليكم.<br>يمكنكم إتمام عملية السداد عبر الرابط التالي: [🔗 رابط الدفع]<br>شاكرين لكم تعاونكم ونتمنى لكم تجربة مميزة مع ✨️🏕️ Funcamp</p>',
                'message_en' => '<p>👋Hello [Customer Name],<br>We are once again sending you the online payment link for your reservation for your convenience.<br>You can complete the payment process via the following link: [🔗 Payment Link]<br>Thank you for your cooperation and we hope you have a great experience with Funcamp✨🏕️</p>',
                'is_active' => true,
                'description' => 'إعادة إرسال رابط الدفع الإلكتروني'
            ],
            [
                'name' => 'تذكير بموعد الحجز',
                'type' => WhatsappMessageTemplate::TYPE_BOOKING_REMINDER,
                'message_ar' => '<p>🌟أهلاً [اسم العميل]،<br>تذكير لطيف 😊 موعد حجزكم معنا في المخيم سيكون يوم [التاريخ] من الساعة [وقت البداية] إلى الساعة [وقت النهاية].<br><br>🔖رقم الحجز: [رقم الحجز]<br>💵المبلغ المدفوع: [المبلغ المدفوع] درهم<br>💵المبلغ المتبقي: [المبلغ المتبقي] درهم<br>💰مبلغ التأمين: [مبلغ التأمين] درهم (مسترجع خلال 24 ساعة بعد التأكد من سلامة المخيم)<br>📍موقع المخيم: [رابط اللوكيشن]<br>☎️مكتب الاستقبال: [الاسم] – [رقم الهاتف] (للمساعدة عند الحاجة)<br><br>ملاحظة هامة: يرجى سداد المبلغ المتبقي لدى مكتب الاستقبال قبل استلام المخيم.<br>بانتظاركم لتستمتعوا بأجواء رائعة وتجربة لا تُنسى مع ✨️🏕️ Funcamp</p>',
                'message_en' => '<p>👋Hello [Customer\'s Name],<br>A gentle reminder 😊 Your camp reservation will be on [Date] from [Start Time] to [End Time].<br><br>🔖Reservation Number: [Reservation Number]<br>💵Amount Paid: [Amount Paid] AED<br>💵Remaining Amount: [Remaining Amount] AED<br>💰Security Deposit: [Security Deposit] AED (Refundable within 24 hours after ensuring the safety of the camp)<br>📍Camp Location: [Location Link]<br>☎️Reception: [Name] – [Phone Number] (For assistance if needed)<br><br>Important Note: Please pay the remaining amount at the reception before arriving at the camp.<br>We look forward to welcoming you to a wonderful atmosphere and an unforgettable experience with Funcamp✨🏕️</p>',
                'is_active' => true,
                'description' => 'تذكير بموعد الحجز'
            ],
            [
                'name' => 'تذكير بقرب انتهاء الحجز',
                'type' => WhatsappMessageTemplate::TYPE_BOOKING_ENDING_REMINDER,
                'message_ar' => '<p>🌟أهلاً [اسم العميل]،<br>😔مع اقتراب انتهاء وقت حجزكم نتمنى لو استمرت تجربتكم معنا أكثر 🏕️✨<br><br>نود تذكيركم بأن موعد انتهاء حجزكم في المخيم (🔖 رقم الحجز: [رقم الحجز]) سيكون الساعة [وقت الخروج].<br>يرجى التكرم بتسجيل الخروج في الموعد المحدد.<br><br>في حال رغبتكم بتمديد فترة الإقامة، يمكنكم التواصل مع مكتب الاستقبال على الرقم: [رقم الهاتف] وذلك وفقاً للشروط والأحكام المعمول بها.<br><br>بانتظاركم دائماً 🏕️ Funcamp لتجارب أجمل مع</p>',
                'message_en' => '<p>👋 Hello [Customer Name],<br>😔 As your reservation time is coming to an end, we hope your experience with us will last longer 🏕️✨<br><br>We would like to remind you that your camp reservation (🔖 Reservation number: [Reservation Number]) will end at [check-out time].<br>Please check out on time.<br><br>If you would like to extend your stay, you may contact the reception at [phone number], in accordance with the applicable terms and conditions.<br><br>We look forward to welcoming you for more enjoyable experiences with Funcamp 🌟</p>',
                'is_active' => true,
                'description' => 'تذكير بقرب انتهاء الحجز'
            ]
        ];

        foreach ($templates as $template) {
            WhatsappMessageTemplate::updateOrCreate(
                ['type' => $template['type']],
                $template
            );
        }
    }
}