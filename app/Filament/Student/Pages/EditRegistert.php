<?php

namespace App\Filament\Student\Pages;

use App\Filament\Resources\RegisterResource\Pages\CreateRegister;
use Filament\Actions\Action as ActionsAction;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\ToggleGroup;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Get;
use Filament\Forms\Components\Wizard;
use Filament\Actions\Action;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use App\Models\Register;

use App\Filament\Resources\RegisterResource\Pages;
use App\Filament\Resources\RegisterResource\RelationManagers;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Section;
use Filament\Forms\Set;
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

class EditRegistert extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'My Registration';
    protected static ?string $title = 'Registration';
    protected static string $view = 'filament.student.pages.edit-registert';

    public function mount()
    {
        
        $register = Register::where('user_id', auth()->user()->id)->first();

        if ($register) {
            $this->form->fill($register->attributesToArray());
            $this->register = $register;
        }
    }

    public function form(Form $form): Form
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
                                ])->required(),
                            TextInput::make('name')->label(__('ชื่อ'))->maxLength(255)->required(),
                            TextInput::make('lastname')->label(__('นามสกุล'))->maxLength(255)->required(),
                            TextInput::make('nickname')->label(__('ชื่อเล่น'))->maxLength(255)->required(),
                            TextInput::make('id_card')->label(__('เลขประจำตัวประชาชน'))->maxLength(13)->required(),
                            DatePicker::make('date_of_birth')->label(__('วันเกิด'))->format('d-m-Y')->required(),
                            Radio::make('gender')->label(__('เพศ'))
                                ->options([
                                    'male' => 'ชาย',
                                    'female' => 'หญิง',
                                ])->columns(2)->required(),
                            TextInput::make('age')->label(__('อายุ'))->required(),
                            TextInput::make('nationality')->label(__('สัญชาติ'))->required(),
                            TextInput::make('ethnicity')->label(__('เชื้อชาติ'))->required(),
                            TextInput::make('address')->label(__('ที่อยู่ตามบัตรประชาชน (กรุณาระบุบ้านเลขที่ หมู่ ซอย ถนน)'))->required(),
                            TextInput::make('district')->label(__('อำเภอ'))->required(),
                            TextInput::make('province')->label(__('จังหวัด'))->required(),
                            TextInput::make('postcode')->label(__('รหัสไปรษณีย์'))->required(),
                            Radio::make('shipping_address')
                                ->label(__('ที่อยู่สำหรับการจัดส่งของรางวัล (กรณีได้รับรางวัล) หากที่อยู่ไม่ตามบัตรประชาชนให้กรอกข้อมูลให้ครบถ้วนในช่องอื่นๆ'))
                                ->options([
                                    'same' => 'เหมือนที่อยู่ตามบัตรประชาชน',
                                    'other' => 'อื่นๆ',
                                ])
                                ->live()
                                ->columns(2)->required(),
                            TextInput::make('shipping_address_detail')
                                ->label(__('ที่อยู่จัดส่ง'))
                                ->placeholder('กรุณาระบุที่อยู่ให้ครบถ้วน')
                                ->default('')
                                ->hidden(fn(Get $get) => $get('shipping_address') !== 'other'),
                            TextInput::make('phone_number')->tel()->label(__('เบอร์โทรศัทพ์ของนักเรียน'))->required(),
                            TextInput::make('email')->label(__('E-mail'))->required(),
                            TextInput::make('line_id')->label(__('Line ID')),
                            TextInput::make('facebook')->label(__('Facebook'))->required(),
                            TextInput::make('name_parent')->label(__('ชื่อนามสกุลผู้ปกครอง'))->required(),
                            TextInput::make('phone_parent')->label(__('เบอร์โทรศัทพ์ผู้ปกครอง'))->required(),
                            Radio::make('sick')->label(__('เคยป่วยเป็นโรคที่ต้องเฝ้าดูอาการอย่างต่อเนื่องหรือไหม (ถ้าเคย โปรดระบุอื่น ๆ)'))
                                ->options([
                                    'no' => 'ไม่เคย',
                                    'yes' => 'เคย',
                                ])->live()->columns(2)->required(),
                            TextInput::make('sick_detail')->label(__('รายละเอียดประวัติเจ็บป่วย'))
                                ->placeholder('โปรดระบุ')
                                ->default('')
                                ->hidden(fn(Get $get) => $get('sick') !== 'yes'),
                            TextInput::make('name_emergency_contact')->label(__('ชื่อผู้ติดต่อในกรณีฉุกเฉิน'))->required(),
                            TextInput::make('phone_emergency_contact')->label(__('เบอร์ติดต่อในกรณีฉุกเฉิน'))->required(),
                            CheckboxList::make('food_allergy')
                                ->label(__('แพ้อาหารหรือไม่ (เช่น อาหารทะเล ฯลฯ)'))
                                ->options([
                                    'yes' => 'แพ้',
                                    'halal' => 'อิสลาม (ไม่กินหมู)',
                                    'no' => 'ไม่แพ้',
                                ])
                                ->live()
                                ->default([]) // Add default empty array
                                ->afterStateUpdated(function ($state, Set $set) {
                                    if (!is_array($state)) {
                                        $state = [];
                                        $set('food_allergy', $state);
                                        return;
                                    }

                                    // If 'yes' is selected, remove 'no'
                                    if (in_array('yes', $state)) {
                                        $state = array_values(array_filter($state, fn($item) => $item !== 'no'));
                                        $set('food_allergy', $state);
                                        return;
                                    }
                                    
                                })->columns(2)->required(),
                            TextInput::make('food_allergy_detail')
                                ->label('อาการแพ้อาหารอื่นๆ')
                                ->placeholder('โปรดระบุ')
                                ->default('')
                                ->hidden(fn(Get $get): bool => !in_array('yes', (array)$get('food_allergy') ?? [])),

                        ])->columns(3),
                    Wizard\Step::make(label: 'Education')
                        ->schema([
                            Select::make('education_status')->label(__('สถานะปัจจุบัน'))
                                ->options([
                                    'm6' => 'กำลังศึกษาชั้นมัธยมศึกษาปีที่ 6',
                                    'endm6' => 'สำเร็จการศึกษาชั้นมัธยมศึกษาปีที่ 6',
                                    'equivalent' => 'เทียบเท่า / อิสระ (Home school / กศน. / ปวช. หรืออื่น ๆ) เฉพาะสายคอมพิวเตอร์หรือเทคโนโลยีเท่านั้น',
                                ])->required(),
                            TextInput::make('name_education')->label(__('ชื่อสถาบัน/โรงเรียน'))->required(),
                            TextInput::make('address_education')->label(__('ที่อยู่สถาบัน/โรงเรียน'))->required(),
                            TextInput::make('province_education')->label(__('จังหวัด'))->required(),
                            Select::make('study_plan')->label(__('แผนการเรียน'))
                                ->options([
                                    'sci_math' => 'สายวิทย์-คณิต',
                                    'sci_tech' => 'สายวิทย์-เทคโนโลยี',
                                    'sci_it' => 'สายวิทย์-คอมพิวเตอร์',
                                    'art_math' => 'สายศิลป์-คำนวณ',
                                    'art_language' => 'สายศิลป์-ภาษา',
                                ])->required(),
                            TextInput::make('gpax')->label(__('เกรดเฉลี่ยรวม'))->required(),
                            TextInput::make('gpa_english')->label(__('เกรดเฉลี่ยรวมวิชาภาษาอังกฤษ'))->required(),
                            TextInput::make('gpa_maths')->label(__('เกรดเฉลี่ยรวมวิชาคณิตศาสตร์'))->required(),
                            CheckboxList::make('experience')->label(__('ประสบการณ์/ความสามารถพิเศษ ที่เกี่ยวข้องกับคอมพิวเตอร์ (สามารถเลือกได้หลายตัวเลือกและเพิ่มเติมได้)'))
                                ->options([
                                    'have' => 'มีประสบการณ์การเขียนโปรแกรมเบื้องต้น',
                                    'join' => 'เคยเข้าค่ายหรือเข้าร่วมกิจกรรมเกี่ยวกับคอมพิวเตอร์/หุ่นยนต์',
                                    'no' => 'ไม่เคยมีประสบการณ์และไม่เคยเข้าค่ายเกี่ยวกับเทคโนโลยีเลย',
                                    'other' => 'อื่นๆ',
                                ])->live()
                                ->default([])
                                ->afterStateUpdated(function ($state, Set $set) {

                                    if (!is_array($state)) {
                                        $state = [];
                                        $set('experience', $state);
                                        return;
                                    }

                                    // If 'no' is selected, clear others
                                    if (in_array('no', $state)) {
                                        $set('experience', ['no']);
                                        return;
                                    }

                                    // If any other option is selected, remove 'no'
                                    if (!empty($state)) {
                                        $state = array_values(array_filter($state, fn($item) => $item !== 'no'));
                                        $set('experience', $state);
                                    }
                                })
                                ->columns(2)->required(),
                            TextInput::make('experience_other')->label(__('รายละเอียดประสบการณ์/ความสามารถพิเศษอื่นๆ'))
                                ->placeholder('โปรดระบุ')
                                ->default('')
                                ->hidden(fn(Get $get): bool => !in_array('other', $get('experience') ?? [])),
                            TextInput::make('reward')->label(__('รางวัล/ประสบการณ์/ความสามารถพิเศษอื่นๆ โปรดระบุ')),
                            TextInput::make('hobby')->label(__('งานอดิเรก')),
                        ])->columns(3),
                    Wizard\Step::make(label: 'Link')
                        ->schema([
                            TextInput::make('link_intro')->label(__('กรุณาแนบ Link คลิปวีดีโอแนะนำตัวประมาณ 3 นาที '))->required(),
                            TextInput::make('link_transcript')->label(__('กรุณาแนบ Link สำหรับไฟล์ ใบประมวลผลการศึกษาถึงปัจจุบัน (Transcript)'))->required(),
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
                                ])->live()
                                ->default([])->required(),
                            TextInput::make('news_other')
                                ->label('โปรดระบุแหล่งข้อมูลอื่นๆ')
                                ->placeholder('กรุณาระบุ')
                                ->hidden(fn(Get $get): bool => !in_array('other', $get('news') ?? [])),
                            Radio::make('pdpa')->label(__('ตามที่จะมีการจัดกิจกรรม และมีการบันทึกภาพวิดิโอ และภาพนิ่งของกิจกรรมม นั้น เนื่องด้วยกฎหมาย PDPA หากมีใบหน้าของข้าพเจ้า ข้าพเจ้ายินยอมที่จะให้ทางวิทยาลัยฯ เผยเเพร่ภาพบันทึกภาพวิดิโอ และภาพนิ่ง ในสื่อสาธารณะชน'))
                                ->options([
                                    'agree' => 'ยินยอม',
                                    'no' => 'ไม่ยินยอม',
                                ])->required(),
                            Radio::make('condition')->label(__('ข้าพเจ้าขอรับรองว่า ข้อความดังกล่าวทั้งหมดในใบสมัครนี้เป็นความจริงทุกประการ หากข้อความในใบสมัครงานเอกสารที่นำมาแสดง หรือรายละเอียดที่ให้ไว้ไม่เป็นความจริง ทางวิทยาลัยศิลปะ สื่อ และเทคโนโลยี มหาวิทยาลัยเชียงใหม่ มีสิทธิ์ที่จะยกเลิกประกาศที่เกี่ยวข้องกับข้าพเจ้าได้ในทันที'))
                                ->options([
                                    'agree' => 'ยินยอม',
                                    'no' => 'ไม่ยินยอม',
                                ])->required(),
                        ]),
                ])->columnSpanFull()
            ])->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $register = Register::where('user_id', auth()->user()->id)->first();
        try {
            $data = $this->form->getState();
            $data['user_id'] = auth()->user()->id;
            if (!$register) {
                $register = new Register();
                $register->user_id = auth()->user()->id;
                $register->create($data);
            } else {
                $register->update($data);
            }

            // $this->redirect(filament()->getHomeUrl());

        } catch (Halt $exception) {
            return;
        }
        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->body('บันทึกสำเร็จ รอเจ้าหน้าที่ติดต่อกลับไป')
            ->send();
    }
}
