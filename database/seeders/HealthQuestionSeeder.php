<?php

namespace Database\Seeders;

use App\Models\HealthQuestion;
use App\Models\HealthQuestionAnswer;
use Illuminate\Database\Seeder;

class HealthQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'text' => 'ท่านมีหรือได้ขอเอาประกันภัยอุบัติเหตุส่วนบุคคล หรือประกันชีวิตไว้กับบริษัทประกันภัยอื่นหรือไม่',
                'answers' => ['ไม่มี', 'มี'],
            ],
            [
                'text' => 'ท่านเคยถูกบริษัทประกันภัยปฏิเสธการรับประกันภัย ยกเลิกประกันภัย หรือเรียกเก็บเบี้ยประกันภัยเพิ่มสำหรับการประกันภัยดังกล่าวหรือไม่',
                'answers' => ['ไม่เคย', 'เคย'],
            ],
            [
                'text' => 'ในระยะเวลา 2 ปีที่ผ่านมา ท่านเคยได้รับบาดเจ็บจากอุบัติเหตุจนต้องเข้ารับการรักษาในโรงพยาบาลในฐานะผู้ป่วยในหรือไม่',
                'answers' => ['ไม่เคย', 'เคย'],
            ],
            [
                'text' => 'ท่านเคยมีหรือมีความผิดปกติของสายตา การได้ยิน หรือระบบประสาท เคยมีอวัยวะส่วนหนึ่งส่วนใดพิการ หรือเคยเสพสารเสพติดให้โทษร้ายแรง หรือเคยต้องโทษในคดีเกี่ยวกับยาเสพติดหรือไม่',
                'answers' => ['ไม่เคย', 'เคย'],
            ],
            [
                'text' => 'ท่านเคยเป็น เคยได้รับการตรวจหรือรักษา กำลังรักษา หรือมีอาการหรือความผิดปกติที่เกี่ยวข้องกับโรคลมชัก โรคหัวใจ โรคความดันโลหิตสูง โรคเบาหวาน โรคกระดูกและ/หรือกล้ามเนื้อ โรคมะเร็ง โรคเอดส์ หรือมีเชื้อไวรัส HIV โรคหลอดเลือดสมอง หรือโรคพิษสุราเรื้อรังหรือไม่',
                'answers' => ['ไม่เคย', 'เคย'],
            ],
        ];

        foreach ($questions as $questionIndex => $item) {
            $question = HealthQuestion::updateOrCreate(
                ['sort_order' => $questionIndex + 1],
                ['question_text' => $item['text']]
            );

            foreach ($item['answers'] as $answerIndex => $answerText) {
                HealthQuestionAnswer::updateOrCreate(
                    [
                        'question_id' => $question->id,
                        'sort_order' => $answerIndex + 1,
                    ],
                    ['answer_text' => $answerText]
                );
            }
        }
    }
}