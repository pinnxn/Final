<?php

namespace App\Filament\Resources;

use App\Filament\Exports\RegisterExporter;
use App\Filament\Imports\RegisterImporter;
use App\Filament\Resources\RegisterResource\Pages;
use App\Filament\Resources\RegisterResource\RelationManagers;
use App\Models\Register;
use Filament\Actions\ImportAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Tabs as ComponentsTabs;
use Filament\Pages\Auth\Register as AuthRegister;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ImportAction as ActionsImportAction;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\ChartWidget;

use function Laravel\Prompts\text;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Filament\Forms\Set;

class RegisterResource extends Resource
{
    protected static ?string $model = Register::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Wizard::make([
                    Wizard\Step::make(label: 'Information')
                        ->schema([
                            TextInput::make('user_id')->label(__('ผู้สมัคร'))->default(User::find(auth()->user()->id)->id)->disabled()->hidden(),
                            Select::make('prefix_name')->label(__('คำนำหน้าชื่อ'))
                                ->options([
                                    'mr' => 'นาย',
                                    'ms' => 'นางสาว',
                                    'mrs' => 'นาง',
                                ]),
                            TextInput::make('name')->label(__('ชื่อ'))->maxLength(255),
                            TextInput::make('lastname')->label(__('นามสกุล'))->maxLength(255),
                            TextInput::make('nickname')->label(__('ชื่อเล่น'))->maxLength(255),
                            TextInput::make('id_card')->label(__('เลขประจำตัวประชาชน'))->maxLength(13),
                            DatePicker::make('date_of_birth')->label(__('วันเกิด'))->format('d-m-Y'),
                            Radio::make('gender')->label(__('เพศ'))
                                ->options([
                                    'male' => 'ชาย',
                                    'female' => 'หญิง',
                                ])->columns(2),
                            TextInput::make('age')->label(__('อายุ')),
                            TextInput::make('nationality')->label(__('สัญชาติ')),
                            TextInput::make('ethnicity')->label(__('เชื้อชาติ')),
                            TextInput::make('address')->label(__('ที่อยู่ตามบัตรประชาชน (กรุณาระบุบ้านเลขที่ หมู่ ซอย ถนน)')),
                            TextInput::make('district')->label(__('อำเภอ')),
                            TextInput::make('province')->label(__('จังหวัด')),
                            TextInput::make('postcode')->label(__('รหัสไปรษณีย์')),
                            Radio::make('shipping_address')
                                ->label(__('ที่อยู่สำหรับการจัดส่งของรางวัล (กรณีได้รับรางวัล) หากที่อยู่ไม่ตามบัตรประชาชนให้กรอกข้อมูลให้ครบถ้วนในช่องอื่นๆ'))
                                ->options([
                                    'same' => 'เหมือนที่อยู่ตามบัตรประชาชน',
                                    'other' => 'อื่นๆ',
                                ])
                                ->live()
                                ->columns(2),
                            TextInput::make('shipping_address_detail')
                                ->label(__('ที่อยู่จัดส่ง'))
                                ->placeholder('กรุณาระบุที่อยู่ให้ครบถ้วน')
                                ->default('')
                                ->hidden(fn(Get $get) => $get('shipping_address') !== 'other'),
                            TextInput::make('phone_number')->tel()->label(__('เบอร์โทรศัทพ์ของนักเรียน')),
                            TextInput::make('email')->label(__('E-mail')),
                            TextInput::make('line_id')->label(__('Line ID')),
                            TextInput::make('facebook')->label(__('Facebook')),
                            TextInput::make('name_parent')->label(__('ชื่อนามสกุลผู้ปกครอง')),
                            TextInput::make('phone_parent')->label(__('เบอร์โทรศัทพ์ผู้ปกครอง')),
                            Radio::make('sick')->label(__('เคยป่วยเป็นโรคที่ต้องเฝ้าดูอาการอย่างต่อเนื่องหรือไหม (ถ้าเคย โปรดระบุอื่น ๆ)'))
                                ->options([
                                    'no' => 'ไม่เคย',
                                    'yes' => 'เคย',
                                ])->live()->columns(2),
                            TextInput::make('sick_detail')->label(__('รายละเอียดประวัติเจ็บป่วย'))
                                ->placeholder('โปรดระบุ')
                                ->default('')
                                ->hidden(fn(Get $get) => $get('sick') !== 'yes'),
                            TextInput::make('name_emergency_contact')->label(__('ชื่อผู้ติดต่อในกรณีฉุกเฉิน')),
                            TextInput::make('phone_emergency_contact')->label(__('เบอร์ติดต่อในกรณีฉุกเฉิน')),
                            CheckboxList::make('food_allergy')->label(__('แพ้อาหารหรือไม่ (เช่น อาหารทะเล ฯลฯ)'))
                                ->options([
                                    'yes' => 'แพ้',
                                    'halal' => 'อิสลาม (ไม่กินหมู)',
                                    'no' => 'ไม่แพ้',
                                ])->live()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    if (!is_array($state)) {
                                        $state = [];
                                        $set('food_allergy', $state);
                                        return;
                                    }

                                    if (in_array('yes', $state)) {
                                        $state = array_values(array_filter($state, fn($item) => $item !== 'no'));
                                        $set('food_allergy', $state);
                                        return;
                                    }
                                })
                                ->columns(2),
                            TextInput::make('food_allergy_detail')
                                ->label('อาการแพ้อาหารอื่นๆ')
                                ->placeholder('โปรดระบุ')
                                ->default('')
                                ->hidden(fn(Get $get): bool => !in_array('yes', $get('food_allergy') ?? [])),
                        ])->columns(3),
                    Wizard\Step::make(label: 'Education')
                        ->schema([
                            Select::make('education_status')->label(__('สถานะปัจจุบัน'))
                                ->options([
                                    'm6' => 'กำลังศึกษาชั้นมัธยมศึกษาปีที่ 6',
                                    'endm6' => 'สำเร็จการศึกษาชั้นมัธยมศึกษาปีที่ 6',
                                    'equivalent' => 'เทียบเท่า / อิสระ (Home school / กศน. / ปวช. หรืออื่น ๆ) เฉพาะสายคอมพิวเตอร์หรือเทคโนโลยีเท่านั้น',
                                ]),
                            TextInput::make('name_education')->label(__('ชื่อสถาบัน/โรงเรียน')),
                            TextInput::make('address_education')->label(__('ที่อยู่สถาบัน/โรงเรียน')),
                            TextInput::make('province_education')->label(__('จังหวัด')),
                            Select::make('study_plan')->label(__('แผนการเรียน'))
                                ->options([
                                    'sci_math' => 'สายวิทย์-คณิต',
                                    'sci_tech' => 'สายวิทย์-เทคโนโลยี',
                                    'sci_it' => 'สายวิทย์-คอมพิวเตอร์',
                                    'art_math' => 'สายศิลป์-คำนวณ',
                                    'art_language' => 'สายศิลป์-ภาษา',
                                ]),
                            TextInput::make('gpax')->label(__('เกรดเฉลี่ยรวม')),
                            TextInput::make('gpa_english')->label(__('เกรดเฉลี่ยรวมวิชาภาษาอังกฤษ')),
                            TextInput::make('gpa_maths')->label(__('เกรดเฉลี่ยรวมวิชาคณิตศาสตร์')),
                            CheckboxList::make('experience')->label(__('ประสบการณ์/ความสามารถพิเศษ ที่เกี่ยวข้องกับคอมพิวเตอร์ (สามารถเลือกได้หลายตัวเลือกและเพิ่มเติมได้)'))
                                ->options([
                                    'have' => 'มีประสบการณ์การเขียนโปรแกรมเบื้องต้น',
                                    'join' => 'เคยเข้าค่ายหรือเข้าร่วมกิจกรรมเกี่ยวกับคอมพิวเตอร์/หุ่นยนต์',
                                    'no' => 'ไม่เคยมีประสบการณ์และไม่เคยเข้าค่ายเกี่ยวกับเทคโนโลยีเลย',
                                    'other' => 'อื่นๆ',
                                ])->live()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    if (!is_array($state)) {
                                        $state = [];
                                        $set('experience', $state);
                                        return;
                                    }

                                    if (in_array('no', $state)) {
                                        $state = array_values(array_filter($state, fn($item) => $item !== 'other'));
                                        $set('experience', $state);
                                        return;
                                    }
                                })
                                ->columns(2),
                            TextInput::make('experience_other')->label(__('รายละเอียดประสบการณ์/ความสามารถพิเศษอื่นๆ'))
                                ->placeholder('โปรดระบุ')
                                ->default('')
                                ->hidden(fn(Get $get): bool => !in_array('other', $get('experience') ?? [])),
                            TextInput::make('reward')->label(__('รางวัล/ประสบการณ์/ความสามารถพิเศษอื่นๆ โปรดระบุ')),
                            TextInput::make('hobby')->label(__('งานอดิเรก')),
                        ])->columns(3),
                    Wizard\Step::make(label: 'Link')
                        ->schema([
                            TextInput::make('link_intro')->label(__('กรุณาแนบ Link คลิปวีดีโอแนะนำตัวประมาณ 3 นาที ')),
                            TextInput::make('link_transcript')->label(__('กรุณาแนบ Link สำหรับไฟล์ ใบประมวลผลการศึกษาถึงปัจจุบัน (Transcript)')),
                            TextInput::make('link_portfolio')->label(__('กรุณาแนบ Link สำหรับไฟล์ Portfolio (ถ้ามี)')),
                            TextInput::make('link_egd')->label(__('กลุ่มเทียบเท่า / อิสระ (Home school / กศน. / ปวช. หรืออื่น ๆ) เฉพาะสายคอมพิวเตอร์หรือเทคโนโลยีเท่านั้น 
กรุณาแนบ Link สำหรับเอกสาร GED   ')),
                        ]),
                    Wizard\Step::make(label: 'File')
                        ->schema([
                            CheckboxList::make('news')
                                ->label(__('ผู้สมัครได้รับข้อมูล/ข่าวสารโครงการจากแหล่งใด'))
                                ->options([
                                    'facebook' => 'Page Facebook DII',
                                    'public_relations' => 'การประชาสัมพันธ์ในโรงเรียน',
                                    'teacher' => 'คุณครูเเนะเเนว',
                                    'parents' => 'ผู้ปกครอง',
                                    'website_dek_DII' => 'Website Dek-D',
                                    'senior_college' => 'รุ่นพี่ในวิทยาลัยศิลปะ สื่อ และเทคโนโลยี',
                                    'friend' => 'เพื่อนแนะนำ',
                                    'other' => 'อื่นๆ',
                                ])->live(),
                            TextInput::make('news_other')
                                ->label('โปรดระบุแหล่งข้อมูลอื่นๆ')
                                ->placeholder('กรุณาระบุ')
                                ->hidden(fn(Get $get): bool => !in_array('other', $get('news') ?? [])),
                            Radio::make('pdpa')->label(__('ตามที่จะมีการจัดกิจกรรม และมีการบันทึกภาพวิดิโอ และภาพนิ่งของกิจกรรมม นั้น เนื่องด้วยกฎหมาย PDPA หากมีใบหน้าของข้าพเจ้า ข้าพเจ้ายินยอมที่จะให้ทางวิทยาลัยฯ เผยเเพร่ภาพบันทึกภาพวิดิโอ และภาพนิ่ง ในสื่อสาธารณะชน'))
                                ->options([
                                    'agree' => 'ยินยอม',
                                    'no' => 'ไม่ยินยอม',
                                ]),
                            Radio::make('condition')->label(__('ข้าพเจ้าขอรับรองว่า ข้อความดังกล่าวทั้งหมดในใบสมัครนี้เป็นความจริงทุกประการ หากข้อความในใบสมัครงานเอกสารที่นำมาแสดง หรือรายละเอียดที่ให้ไว้ไม่เป็นความจริง ทางวิทยาลัยศิลปะ สื่อ และเทคโนโลยี มหาวิทยาลัยเชียงใหม่ มีสิทธิ์ที่จะยกเลิกประกาศที่เกี่ยวข้องกับข้าพเจ้าได้ในทันที'))
                                ->options([
                                    'agree' => 'ยินยอม',
                                    'no' => 'ไม่ยินยอม',
                                ]),
                        ]),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('prefix_name')
                    ->label(__('คำนำหน้าชื่อ'))
                    ->toggleable()
                    ->searchable()
                    ->formatStateUsing(function (string $state): string {
                        $prefixNames = [
                            'mr' => 'นาย',
                            'ms' => 'นางสาว',
                            'mrs' => 'นาง',
                        ];
                        return $prefixNames[$state] ?? $state;
                    }),
                TextColumn::make('name')
                    ->label(__('ชื่อ'))
                    ->toggleable()
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('name', 'LIKE BINARY', "%{$search}%");
                    }),
                TextColumn::make('lastname')
                    ->label(__('นามสกุล'))
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nickname')
                    ->label(__('ชื่อเล่น'))
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('id_card')->label(__('รหัสประจำตัวประชาชน'))->toggleable(),
                TextColumn::make('date_of_birth')->label(__('วันเกิด'))->date('d-m-Y')->toggleable(),
                TextColumn::make('age')->label(__('อายุ'))->toggleable(),
                TextColumn::make('nationality')->label(__('สัญชาติ'))->toggleable(),
                TextColumn::make('ethnicity')->label(__('เชื้อชาติ'))->toggleable()->searchable(),
                TextColumn::make('gender')->label(__('เพศ'))->toggleable()
                    ->formatStateUsing(function (string $state): string {
                        $genders = [
                            'male' => 'ชาย',
                            'female' => 'หญิง',
                        ];
                        return $genders[$state] ?? $state;
                    }),
                TextColumn::make('address')->label(__('ที่อยู่ตามบัตรประชาชน'))->toggleable()->searchable(),
                TextColumn::make('district')->label(__('อำเภอ'))->toggleable()->sortable()->searchable(),
                TextColumn::make('province')->label(__('จังหวัด'))->toggleable()->sortable()->searchable(),
                TextColumn::make('postcode')->label(__('รหัสไปรษณีย์'))->toggleable()->sortable()->searchable(),
                TextColumn::make('shipping_address')->label(__('ที่อยู่สำหรับการจัดส่งของรางวัล (กรณีได้รับรางวัล) หากที่อยู่ไม่ตามบัตรประชาชนให้กรอกข้อมูลให้ครบถ้วนในช่องอื่นๆ'))
                    ->formatStateUsing(function ($record): string {
                        if ($record->shipping_address === 'same') return 'เหมือนที่อยู่ตามบัตรประชาชน';
                        return 'อื่นๆ: ' . $record->shipping_address_detail;
                    }),
                TextColumn::make('phone_number')->label(__('เบอร์โทรศัทพ์ของนักเรียน'))->toggleable()->searchable(),
                TextColumn::make('email')->label(__('E-mail'))->toggleable()->searchable(),
                TextColumn::make('line_id')->label(__('Line ID'))->toggleable()->searchable(),
                TextColumn::make('facebook')->label(__('Facebook'))->toggleable()->searchable(),
                TextColumn::make('name_parent')
                    ->label(__('ชื่อนามสกุลผู้ปกครอง'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('phone_parent')->label(__('เบอร์โทรศัทพ์ผู้ปกครอง'))->toggleable()->searchable(),
                TextColumn::make('sick')->label(__('เคยป่วยเป็นโรคที่ต้องเฝ้าดูอาการอย่างต่อเนื่องหรือไหม (ถ้าเคย โปรดระบุอื่น ๆ)'))
                    ->formatStateUsing(function ($record): string {
                        if ($record->sick === 'no') return 'ไม่เคย';
                        return 'เคย: ' . $record->sick_detail;
                    }),
                TextColumn::make('name_emergency_contact')
                    ->label(__('ชื่อผู้ติดต่อในกรณีฉุกเฉิน'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('phone_emergency_contact')->label(__('เบอร์ติดต่อในกรณีฉุกเฉิน'))->toggleable()->searchable(),
                TextColumn::make('food_allergy')
                    ->label(__('แพ้อาหารหรือไม่ (เช่น อาหารทะเล ฯลฯ)'))
                    ->formatStateUsing(function ($record): string {
                        if (empty($record->food_allergy)) {
                            return 'ไม่แพ้';
                        }

                        $allergies = collect($record->food_allergy)->map(function ($value) use ($record) {
                            return match ($value) {
                                'halal' => 'อิสลาม (ไม่กินหมู)',
                                'yes' => $record->food_allergy_detail ? "แพ้: {$record->food_allergy_detail}" : 'แพ้',
                                'no' => 'ไม่แพ้',
                                default => $value
                            };
                        });

                        return $allergies->implode(', ');
                    }),
                TextColumn::make('education_status')->label(__('สถานะปัจจุบัน'))->toggleable()->formatStateUsing(function (string $state): string {
                    $educationStatuses = [
                        'm6' => 'กำลังศึกษาชั้นมัธยมศึกษาปีที่ 6',
                        'endm6' => 'สำเร็จการศึกษาชั้นมัธยมศึกษาปีที่ 6',
                        'equivalent' => 'เทียบเท่า / อิสระ (Home school / กศน. / ปวช. หรืออื่น ๆ) เฉพาะสายคอมพิวเตอร์หรือเทคโนโลยีเท่านั้น',
                    ];
                    return $educationStatuses[$state] ?? $state;
                }),
                TextColumn::make('name_education')
                    ->label(__('ชื่อสถาบัน/โรงเรียน'))
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('address_education')->label(__('ที่อยู่สถาบัน/โรงเรียน'))->toggleable()->searchable(),
                TextColumn::make('province_education')->label(__('จังหวัด'))->toggleable()->searchable(),
                TextColumn::make('study_plan')->label(__('แผนการเรียน'))->toggleable()
                    ->formatStateUsing(function (string $state): string {
                        $studyPlans = [
                            'sci_math' => 'สายวิทย์-คณิต',
                            'sci_tech' => 'สายวิทย์-เทคโนโลยี',
                            'sci_it' => 'สายวิทย์-คอมพิวเตอร์',
                            'art_math' => 'สายศิลป์-คำนวณ',
                            'art_language' => 'สายศิลป์-ภาษา',
                        ];
                        return $studyPlans[$state] ?? $state;
                    }),
                TextColumn::make('gpax')->label(__('เกรดเฉลี่ยรวม'))->toggleable()->sortable()->searchable(),
                TextColumn::make('gpa_english')->label(__('เกรดเฉลี่ยรวมวิชาภาษาอังกฤษ'))->toggleable()->sortable()->searchable(),
                TextColumn::make('gpa_maths')->label(__('เกรดเฉลี่ยรวมวิชาคณิตศาสตร์'))->toggleable()->sortable()->searchable(),
                TextColumn::make('experience')
                    ->label(__('ประสบการณ์/ความสามารถพิเศษ ที่เกี่ยวข้องกับคอมพิวเตอร์'))
                    ->formatStateUsing(function ($record): string {
                        if (empty($record->experience)) {
                            return '';
                        }

                        $experiences = collect($record->experience)->map(function ($value) use ($record) {
                            return match ($value) {
                                'have' => 'มีประสบการณ์การเขียนโปรแกรมเบื้องต้น',
                                'join' => 'เคยเข้าค่ายหรือเข้าร่วมกิจกรรมเกี่ยวกับคอมพิวเตอร์/หุ่นยนต์',
                                'no' => 'ไม่เคยมีประสบการณ์และไม่เคยเข้าค่ายเกี่ยวกับเทคโนโลยีเลย',
                                'other' => $record->experience_other ? "อื่นๆ: {$record->experience_other}" : 'อื่นๆ',
                                default => $value
                            };
                        });

                        return $experiences->implode(', ');
                    }),
                TextColumn::make('reward')->label(__('รางวัล/ประสบการณ์/ความสามารถพิเศษอื่นๆ โปรดระบุ'))->toggleable()->searchable(),
                TextColumn::make('hobby')->label(__('งานอดิเรก'))->toggleable(),
                TextColumn::make('link_intro')->label(__('กรุณาแนบ Link คลิปวีดีโอแนะนำตัวประมาณ 3 นาที '))->toggleable(),
                TextColumn::make('link_transcript')->label(__('กรุณาแนบ Link สำหรับไฟล์ ใบประมวลผลการศึกษาถึงปัจจุบัน (Transcript)'))->toggleable(),
                TextColumn::make('link_portfolio')->label(__('กรุณาแนบ Link สำหรับไฟล์ Portfolio (ถ้ามี)'))->toggleable(),
                TextColumn::make('link_egd')->label(__('กลุ่มเทียบเท่า / อิสระ (Home school / กศน. / ปวช. หรืออื่น ๆ) เฉพาะสายคอมพิวเตอร์หรือเทคโนโลยีเท่านั้น กรุณาแนบ Link สำหรับเอกสาร GED'))->toggleable(),
                TextColumn::make('news')
                    ->label(__('ผู้สมัครได้รับข้อมูล/ข่าวสารโครงการจากแหล่งใด'))
                    ->formatStateUsing(function ($record): string {
                        // Convert string to array if needed
                        $newsKeys = is_array($record->news) ? $record->news : explode(',', $record->news);

                        // Map each key to its corresponding display value
                        $mappedValues = collect($newsKeys)
                            ->map(fn($key) => match ($key) {
                                'facebook' => 'Page Facebook DII',
                                'public_relations' => 'การประชาสัมพันธ์ในโรงเรียน',
                                'teacher' => 'คุณครูเเนะเเนว',
                                'parents' => 'ผู้ปกครอง',
                                'website_dek_DII' => 'Website Dek-D',
                                'senior_college' => 'รุ่นพี่ในวิทยาลัยศิลปะ สื่อ และเทคโนโลยี',
                                'friend' => 'เพื่อนแนะนำ',
                                'other' => $record->news_other ? "อื่นๆ: {$record->news_other}" : 'อื่นๆ',
                                default => $key
                            });

                        return $mappedValues->implode(', ');
                    }),
                TextColumn::make('pdpa')->label(__('ตามที่จะมีการจัดกิจกรรม และมีการบันทึกภาพวิดิโอ และภาพนิ่งของกิจกรรมม นั้น เนื่องด้วยกฎหมาย PDPA หากมีใบหน้าของข้าพเจ้า ข้าพเจ้ายินยอมที่จะให้ทางวิทยาลัยฯ เผยเเพร่ภาพบันทึกภาพวิดิโอ และภาพนิ่ง ในสื่อสาธารณะชน'))
                    ->formatStateUsing(function (string $state): string {
                        $pdpaStatuses = [
                            'agree' => 'ยินยอม',
                            'no' => 'ไม่ยินยอม',
                        ];
                        return $pdpaStatuses[$state] ?? $state;
                    }),
                TextColumn::make('condition')->toggleable()->label(__('ข้าพเจ้าขอรับรองว่า ข้อความดังกล่าวทั้งหมดในใบสมัครนี้เป็นความจริงทุกประการ หากข้อความในใบสมัครงานเอกสารที่นำมาแสดง หรือรายละเอียดที่ให้ไว้ไม่เป็นความจริง ทางวิทยาลัยศิลปะ สื่อ และเทคโนโลยี มหาวิทยาลัยเชียงใหม่ มีสิทธิ์ที่จะยกเลิกประกาศที่เกี่ยวข้องกับข้าพเจ้าได้ในทันที'))
                    ->formatStateUsing(function (string $state): string {
                        $conditionStatuses = [
                            'agree' => 'ยินยอม',
                            'no' => 'ไม่ยินยอม',
                        ];
                        return $conditionStatuses[$state] ?? $state;
                    }),
                TextColumn::make('created_at')->dateTime('d-m-Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                // ExportAction::make()
                //     ->exporter(RegisterExporter::class)
                //     ->filename('registers'),

                // // Fix: Use ActionsImportAction instead of ImportAction
                // ActionsImportAction::make()
                //     ->importer(RegisterImporter::class)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make()->exporter(RegisterExporter::class)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegisters::route('/'),
            'create' => Pages\CreateRegister::route('/create'),
            'edit' => Pages\EditRegister::route('/{record}/edit'),
        ];
    }
}
