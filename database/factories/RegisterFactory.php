<?php

namespace Database\Factories;

use App\Models\Register;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use App\Models\User;
class RegisterFactory extends Factory
{
    protected $model = Register::class;

    public function definition(): array
    {
        $user_id = "1";
        $gender = $this->faker->randomElement(['male', 'female']);
        $prefix_name = match ($gender) {
            'male' => 'mr',
            'female' => $this->faker->randomElement(['ms', 'mrs']),
        };

        $isShippingSame = $this->faker->boolean(80);
        $hasSickness = $this->faker->boolean(20);
        $hasAllergy = $this->faker->boolean(30);

        // Thai names based on gender
        $thaiNames = [
            'male' => [
                'ธนกร','ภูมิพัฒน์','ณัฐวุฒิ','กิตติพงศ์','ศุภณัฐ',
                'ธีรภัทร', 'พงศกร','ปิยะพงษ์','ชยพล','วรเมธ',
                'สมชาย','วันดี','ประเสริฐ','สุชาติ','สมศักดิ์',
                'สมศักดิ์','วิชัย','อนุชา','ชาญชัย','ประพันธ์','กมลา',
            ],
            'female' => [
                'กัญญา','ณัฐธิดา','ปิยะธิดา','วรรณวิสา','ศิริพร',
                'สุภาพร','อริสา','พิมพ์มาดา','ญาดา','ณัฐชา',
                'สุภาวดี','รัตนา','มาลี','พิมพ์ใจ','กมลา',
                'วาสนา','อริสา','พิมพ์มาดา','ญาดา','ณัฐชา',
            ]
        ];

        $thaiLastNames = [
            'รักษ์ไทย','สมบูรณ์พงศ์','วงศ์สุวรรณ','ศรีวิไล','พงษ์เพชร',
            'ธนบดี','ภูวดล','สุขสวัสดิ์','รุ่งเรือง','มั่นคง',
            'ภูวดล','อินทรวิเชียร','กิจเจริญ','บุญประเสริฐ','พัฒนาไพร',
            'อมรรัตน์','ไพบูลย์','อำนาจ','มีสุข','อมราพร',
        ];

        $thaiNicknames = [
            'แบงค์','เบนซ์',     
            'บอส',     
            'เบียร์',   
            'บีม',      
            'เอิร์ธ',   
            'ไอซ์',     
            'เจ',       
            'มิว',     
            'แพท',     
            'พลอย',    
            'แพร',    
            'ปิ่น',      
            'มิ้นท์',     
            'เฟิร์น',     
            'นิว',      
            'เบล',      
            'กิ๊ฟ',     
            'แนท',     
            'เอม',     
            'ปอ',     
            'เบส',      
            'โอม',     
            'ต้น',    
            'เก่ง',     
            'ฟลุ๊ค',    
            'บูม',    
            'เอ็ม',      
            'ปาล์ม',  
            'ปัน',      
            'เพชร',    
            'ภูมิ',      
            'ปุ๊ก',      
            'ริว',      
            'โรส',      
            'เซน',    
            'สกาย',    
            'ซัน',      
            'ต้าร์',     
            'วิน',      
        ];

        return [
            'prefix_name' => $prefix_name,
            'name' => $this->faker->randomElement($thaiNames[$gender]),
            'lastname' => $this->faker->randomElement($thaiLastNames),
            'nickname' => $this->faker->randomElement($thaiNicknames),
            'id_card' => $this->faker->numerify('#############'),
            'date_of_birth' => Carbon::now()->subYears(rand(17, 19))->subMonths(rand(0, 11))->format('Y-m-d'),
            'gender' => $gender,
            'age' => $this->faker->numberBetween(17, 19),
            'nationality' => 'ไทย',
            'ethnicity' => 'ไทย',
            'address' => $this->faker->numberBetween(1, 999) . ' หมู่ ' . $this->faker->numberBetween(1, 20) . ' ต.' . $this->faker->randomElement(['สุเทพ', 'ช้างเผือก', 'ศรีภูมิ', 'พระสิงห์', 'หายยา']),
            'district' => $this->faker->randomElement(['เมืองเชียงใหม่', 'สันทราย', 'สันกำแพง', 'หางดง', 'สารภี', 'แม่ริม']),
            'province' => $this->faker->randomElement([
                'เชียงใหม่',
                'เชียงราย',
                'ลำพูน',
                'ลำปาง',
                'แพร่',
                'น่าน',
                'พะเยา',
                'แม่ฮ่องสอน',
                'กรุงเทพมหานคร',
                'นนทบุรี',
                'สมุทรสาคร',
            ]),
            'postcode' => $this->faker->randomElement(['50200', '50300', '50100', '10200', '10110']),
            'shipping_address' => $isShippingSame ? 'same' : 'other',
            'shipping_address_detail' => $isShippingSame ? null : 'บ้านเลขที่ ' . $this->faker->numberBetween(1, 999) . ' หมู่ ' . $this->faker->numberBetween(1, 20),
            'phone_number' => '0' . $this->faker->numberBetween(8, 9) . $this->faker->numerify('########'),
            'email' => $this->faker->unique()->safeEmail(),
            'line_id' => $this->faker->userName(),
            'facebook' => $this->faker->userName(),
            'name_parent' => $this->faker->randomElement($thaiNames['male']) . ' ' . $this->faker->randomElement($thaiLastNames),
            'phone_parent' => '0' . $this->faker->numberBetween(8, 9) . $this->faker->numerify('########'),
            'sick' => $hasSickness ? 'yes' : 'no',
            'sick_detail' => $hasSickness ? $this->faker->randomElement(['โรคภูมิแพ้', 'โรคหอบหืด', 'โรคกระเพาะ']) : null,
            'name_emergency_contact' => $this->faker->randomElement($thaiNames['female']) . ' ' . $this->faker->randomElement($thaiLastNames),
            'phone_emergency_contact' => '0' . $this->faker->numberBetween(8, 9) . $this->faker->numerify('########'),
            'food_allergy' => $hasAllergy ?
                $this->faker->randomElements(['yes', 'halal', 'no'], $this->faker->numberBetween(1, 2)) :
                ['no'],
            'food_allergy_detail' => $hasAllergy ? $this->faker->randomElement(['อาหารทะเล', 'ถั่ว', 'ไข่', 'นม']) : null,
            'education_status' => $this->faker->randomElement(['m6', 'endm6', 'equivalent']),
            'name_education' => $this->faker->randomElement([
                'โรงเรียนเชียงใหม่คริสเตียน',
                'โรงเรียนดาราวิทยาลัย',
                'โรงเรียนปรินส์รอยแยลส์วิทยาลัย',
                'โรงเรียนมงฟอร์ตวิทยาลัย',
                'โรงเรียนวัฒโนทัยพายัพ',
                'โรงเรียนยุพราชวิทยาลัย',
                'โรงเรียนสาธิตมหาวิทยาลัยเชียงใหม่',
                'โรงเรียนเรยีนาเชลีวิทยาลัย'
            ]),
            'address_education' => $this->faker->numberBetween(1, 999) . ' ถ.' . $this->faker->randomElement(['ช้างเผือก', 'นิมมานเหมินท์', 'สุเทพ', 'ห้วยแก้ว']),
            'province_education' => 'เชียงใหม่',
            'study_plan' => $this->faker->randomElement([
                'sci_math',
                'sci_tech',
                'sci_it',
                'art_math',
                'art_language'
            ]),
            'gpax' => $this->faker->randomFloat(2, 3.0, 4.0),
            'gpa_english' => $this->faker->randomFloat(2, 2.5, 4.0),
            'gpa_maths' => $this->faker->randomFloat(2, 2.5, 4.0),
            'experience' => $this->faker->randomElements(
                ['have', 'join', 'no', 'other'],
                $this->faker->numberBetween(1, 3)
            ),
            'experience_other' => 'เคยเข้าร่วมการแข่งขัน' . $this->faker->randomElement(['โครงงานคอมพิวเตอร์', 'การเขียนโปรแกรม', 'หุ่นยนต์']),
            'reward' => $this->faker->randomElement([
                'รางวัลชนะเลิศการแข่งขันคอมพิวเตอร์ระดับจังหวัด',
                'รางวัลรองชนะเลิศการแข่งขันหุ่นยนต์',
                'เหรียญทองการแข่งขันโครงงานคอมพิวเตอร์',
                'รางวัลชมเชยการแข่งขันเขียนโปรแกรม'
            ]),
            'hobby' => implode(', ', $this->faker->randomElements([
                'เขียนโปรแกรม',
                'เล่นเกม',
                'ดูหนัง',
                'ฟังเพลง',
                'อ่านหนังสือ',
                'วาดรูป',
                'เล่นดนตรี',
                'เล่นกีฬา'
            ], 2)),
            'link_intro' => 'https://www.youtube.com/watch?v=' . $this->faker->regexify('[A-Za-z0-9]{11}'),
            'link_transcript' => 'https://drive.google.com/' . $this->faker->uuid,
            'link_portfolio' => 'https://drive.google.com/' . $this->faker->uuid,
            'link_egd' => 'https://drive.google.com/' . $this->faker->uuid,
            'news' => $this->faker->randomElements(
                ['facebook', 'public_relations', 'teacher', 'parents', 'website_dek_DII', 'senior_college', 'friend', 'other'],
                $this->faker->numberBetween(1, 3)
            ),
            'news_other' => $this->faker->randomElement(['Instagram', 'Twitter', 'TikTok', 'YouTube']),
            'pdpa' => 'agree',
            'condition' => 'agree',
            'user_id' => $user_id,
        ];
    }
}
