<?php

namespace App\Filament\Exports;

use App\Models\Register;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class RegisterExporter extends Exporter
{
    protected static ?string $model = Register::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ลำดับที่'),
            ExportColumn::make('prefix_name')->label('คำนำหน้า'),
            ExportColumn::make('name')->label('ชื่อ'),
            ExportColumn::make('lastname')->label('นามสกุล'),
            ExportColumn::make('nickname')->label('ชื่อเล่น'),
            ExportColumn::make('id_card')->label('รหัสประจำตัวประชาชน'),
            ExportColumn::make('date_of_birth')->label('วันเกิด'),
            ExportColumn::make('gender')->label('เพศ'),
            ExportColumn::make('age')->label('อายุ'),
            ExportColumn::make('nationality')->label('สัญชาติ'),
            ExportColumn::make('ethnicity')->label('ชนชาติ'),
            ExportColumn::make('address')->label('ที่อยู่'),
            ExportColumn::make('province')->label('จังหวัด'),
            ExportColumn::make('postcode')->label('รหัสไปรษณีย์'),
            ExportColumn::make('phone_number')->label('เบอร์โทรศัทพ์'),
            ExportColumn::make('email')->label('อีเมล'),
            ExportColumn::make('line_id')->label('Line'),
            ExportColumn::make('facebook')->label('Facebook'),
            ExportColumn::make('name_parent')->label('ชื่อผู้ปกครอง'),
            ExportColumn::make('phone_parent')->label('เบอร์โทรศัทพ์ผู้ปกครอง'),
            ExportColumn::make('sick')->label('อาการป่วย'),
            ExportColumn::make('name_emergency_contact')->label('ชื่อผู้ติดต่อฉุกเฉิน'),
            ExportColumn::make('phone_emergency_contact')->label('เบอร์โทรศัทพ์ผู้ติดต่อฉุกเฉิน'),
            ExportColumn::make('food_allergy')->label('อาการแพ้อาหาร'),
            ExportColumn::make('education_status')->label('สถานะการศึกษา'),
            ExportColumn::make('name_education')->label('ชื่อสถานศึกษา'),
            ExportColumn::make('address_education')->label('ที่อยู่สถานศึกษา'),
            ExportColumn::make('province_education')->label('จังหวัดสถานศึกษา'),
            ExportColumn::make('study_plan')->label('แผนการเรียน'),
            ExportColumn::make('gpax')->label('เกรดเฉลี่ยรวม'),
            ExportColumn::make('gpa_english')->label('เกรดเฉลี่ยภาษาอังกฤษ'),
            ExportColumn::make('gpa_maths')->label('เกรดเฉลี่ยคณิตศาสตร์'),
            ExportColumn::make('experience')->label('ประสบการณ์'),
            ExportColumn::make('reward')->label('รางวัล'),
            ExportColumn::make('hobby')->label('งานอดิเรก'),
            ExportColumn::make('link_intro')->label('คลิปวีดีโอแนะนำตัว'),
            ExportColumn::make('link_transcript')->label('ลิงค์สำหรับไฟล์ Transcript'),
            ExportColumn::make('link_portfolio')->label('ลิงค์สำหรับไฟล์ Portfolio'),
            ExportColumn::make('link_egd')->label('ลิงค์สำหรับเอกสาร GED'),
            ExportColumn::make('info')->label('ข้อมูลที่ต้องการรับข้อมูล'),
            ExportColumn::make('pdpa')->label('ข้อมูลส่วนบุคคล'),
            ExportColumn::make('condition')->label('ข้อตกลง'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your register export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
