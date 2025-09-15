<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WhatsappMessageTemplate;

class ManualTemplateSeeder extends Seeder
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
                'name' => 'ترحيب عام',
                'type' => WhatsappMessageTemplate::TYPE_MANUAL_TEMPLATE,
                'message_ar' => '<p>🌟أهلاً وسهلاً [اسم العميل]،<br>نرحب بكم في Funcamp ونتمنى لكم تجربة مميزة معنا.<br>نحن هنا لخدمتكم في أي وقت.<br>مع تحيات فريق Funcamp 🏕️✨</p>',
                'message_en' => '<p>👋 Welcome [Customer Name],<br>We welcome you to Funcamp and hope you have a wonderful experience with us.<br>We are here to serve you at any time.<br>Best regards, Funcamp Team 🏕️✨</p>',
                'is_active' => true,
                'description' => 'رسالة ترحيب عامة للعملاء'
            ],
            [
                'name' => 'تأكيد الحجز',
                'type' => WhatsappMessageTemplate::TYPE_MANUAL_TEMPLATE,
                'message_ar' => '<p>✅تم تأكيد حجزكم بنجاح [اسم العميل]،<br>تفاصيل الحجز:<br>📅 التاريخ: [التاريخ]<br>⏰ الوقت: [الوقت]<br>📍 الموقع: [الموقع]<br>ننتظركم لتستمتعوا بتجربة لا تُنسى معنا!<br>مع تحيات Funcamp 🏕️</p>',
                'message_en' => '<p>✅Your reservation has been confirmed successfully [Customer Name],<br>Reservation Details:<br>📅 Date: [Date]<br>⏰ Time: [Time]<br>📍 Location: [Location]<br>We look forward to welcoming you for an unforgettable experience!<br>Best regards, Funcamp 🏕️</p>',
                'is_active' => true,
                'description' => 'رسالة تأكيد الحجز للعملاء'
            ],
            [
                'name' => 'تذكير عام',
                'type' => WhatsappMessageTemplate::TYPE_MANUAL_TEMPLATE,
                'message_ar' => '<p>🔔تذكير مهم [اسم العميل]،<br>نود تذكيركم بـ [الموضوع].<br>يرجى التواصل معنا في حالة وجود أي استفسارات.<br>نحن هنا لمساعدتكم دائماً.<br>مع تحيات Funcamp 📞</p>',
                'message_en' => '<p>🔔Important Reminder [Customer Name],<br>We would like to remind you about [Subject].<br>Please contact us if you have any questions.<br>We are always here to help you.<br>Best regards, Funcamp 📞</p>',
                'is_active' => true,
                'description' => 'رسالة تذكير عامة للعملاء'
            ],
            [
                'name' => 'شكر وتقدير',
                'type' => WhatsappMessageTemplate::TYPE_MANUAL_TEMPLATE,
                'message_ar' => '<p>🙏شكراً جزيلاً [اسم العميل]،<br>نشكركم على ثقتكم في Funcamp وعلى اختياركم لنا.<br>نقدر تعاونكم ونتمنى أن تكون تجربتكم معنا ممتعة.<br>نتطلع لخدمتكم مرة أخرى قريباً.<br>مع أطيب التحيات، فريق Funcamp 💚</p>',
                'message_en' => '<p>🙏Thank you very much [Customer Name],<br>We thank you for your trust in Funcamp and for choosing us.<br>We appreciate your cooperation and hope your experience with us was enjoyable.<br>We look forward to serving you again soon.<br>Best regards, Funcamp Team 💚</p>',
                'is_active' => true,
                'description' => 'رسالة شكر وتقدير للعملاء'
            ],
            [
                'name' => 'إشعار خاص',
                'type' => WhatsappMessageTemplate::TYPE_MANUAL_TEMPLATE,
                'message_ar' => '<p>📢إشعار مهم [اسم العميل]،<br>نود إعلامكم بـ [المحتوى].<br>هذا الإشعار مهم ويحتاج انتباهكم.<br>يرجى قراءة التفاصيل بعناية والرد في أقرب وقت ممكن.<br>شاكرين لكم انتباهكم، فريق Funcamp 📋</p>',
                'message_en' => '<p>📢Important Notice [Customer Name],<br>We would like to inform you about [Content].<br>This notice is important and requires your attention.<br>Please read the details carefully and respond as soon as possible.<br>Thank you for your attention, Funcamp Team 📋</p>',
                'is_active' => true,
                'description' => 'رسالة إشعار خاص للعملاء'
            ]
        ];

        foreach ($templates as $templateData) {
            WhatsappMessageTemplate::updateOrCreate(
                ['type' => $templateData['type'], 'name' => $templateData['name']],
                $templateData
            );
        }

        $this->command->info('Manual templates seeded successfully!');
    }
}