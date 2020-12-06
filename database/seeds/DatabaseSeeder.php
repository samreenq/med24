<?php

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    use \Spatie\Translatable\HasTranslations;

    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function __construct()
    {
        app()->setLocale('en');
    }

    public function run()
    {
//        $this->createCountries();
//        $this->createLanguages();
//        $this->createHospitals(50);
//        $this->createSpecialities();
//        $this->createCertifications();
//            $this->createDoctors(20);
//        $this->assignCertifications();
//        $this->doctorLanguages();
//        $this->assignHospitals();
//        $this->assignSpecialites();
//        $this->createPatient(50);
//        $this->createPatientFamilyMembers();
//        $this->createAppointments(10);
//        $this->createReviews(50);
//        $this->createReplies(100);
//        $this->createUAECities();
    }



    public function createDoctors($limit){
        $faker = Faker\Factory::create();
        for($i=0;$i<=$limit;$i++){
            $doctor= \App\Doctor::create([
                'first_name'=>$faker->firstName,
                'last_name'=>$faker->lastName,
                'email'=>$faker->freeEmail,
                'gender'=>$faker->randomElement(['male','female']),
                'phone'=>$faker->phoneNumber,
                'biography'=>$faker->paragraph,
                'summary'=>$faker->paragraph,
                'care_philosophy'=>$faker->parse('Early To Bed Early To Rise , Makes A Man Healthy Wealthy And Wise'),
                'status'=>1,
                'ip_address'=>$faker->ipv4,
                'medical_license'=>$faker->numerify('Md23M23'),
                'image'=>'doctor.png',
                'password'=>bcrypt('12345678'),
                'api_token'=>bcrypt($faker->firstName)
            ]);

        }
    }


    public function createHospitals($limit){
        $faker = Faker\Factory::create();
        for($i=0;$i<=$limit;$i++){
            \App\Hospital::create([
                'name'=>$faker->company,
                'email'=>$faker->email,
                'password'=>bcrypt('12345678'),
                'logo'=>'hospital.png',
                'image'=>'hospital.png',
                'status'=>1,
                'description'=>$faker->paragraph,
                'phone'=>$faker->phoneNumber,
                'address'=>$faker->streetAddress
            ]);
        }
    }


    public function createSpecialities(){
        $specialities=[
            'ALLERGY & IMMUNOLOGY',
            'ANESTHESIOLOGY',
            'DERMATOLOGY',
            'DIAGNOSTIC RADIOLOGY',
            'EMERGENCY MEDICINE',
            'FAMILY MEDICINE',
            'INTERNAL MEDICINE',
            'NEUROLOGY',
            'Nephrology',
            'Oncology',
            'Hematology',
            'OPHTHALMOLOGY',
            'PATHOLOGY',
            'Cardiology'

        ];
        foreach ($specialities as $speciality){
            \App\Speciality::create([
                'name'=>ucfirst($speciality),
                'status'=>1,
            ]);
        }
    }
    public function createCertifications(){
        $certifications=[
            'CCS-P Certified Coding Specialist-Physician-based',
            'CPCT Certified Patient Care Technician',
            'CET Certified EKG Technician',
            'CPT Certified Phlebotomy Technician',
            'CCMA Certified Clinical Medical Assistant',
            'Radiation Health and Safety, and Infection Control Exams',
            'Allied Healthcare Professional',
            'Healthcare Administration Professional'
        ];
        foreach ($certifications as $cer){
            \App\Certification::create([
                'name'=>$cer
            ]);
        }
    }

    public function assignCertifications(){
        $doctors=\App\Doctor::all();
        foreach ($doctors as $doctor){
            $certifications=\App\Certification::inRandomOrder()->limit(2)->get()->pluck('id')->toArray();
            $doctor->certifications()->sync($certifications);
        }
    }

    public function createPatient($limit){
        $faker = Faker\Factory::create();
        for($i=0;$i<=$limit;$i++){
            \App\Patient::create([
                'first_name'=>$faker->firstName,
                'last_name'=>$faker->lastName,
                'email'=>$faker->email,
                'gender'=>$faker->randomElement(['male','female']),
                'date_of_birth'=>$faker->date(),
                'phone'=>$faker->phoneNumber,
                'status'=>1,
                'ip_address'=>$faker->ipv4,
                'image'=>'patient.png',
                'password'=>bcrypt('12345678'),
                'api_token'=>bcrypt($faker->firstName)
            ]);
        }
    }


    public function createAppointments($limit){
        $faker = Faker\Factory::create();
        $doctors=\App\Doctor::whereHas('hospitals')->get();
        foreach ($doctors as $doctor){
            for($i=0;$i<=$limit;$i++){
                $patient=\App\Patient::inRandomOrder()->first();
                \App\Appointment::create([
                    'doctor_id'=>$doctor->id,
                    'patient_id'=>$patient->id,
                    'hospital_id'=>$doctor->hospitals[0]->id,
                    'appointment_time'=>$faker->time(),
                    'appointment_date'=>$faker->date(),
                    'family_member_id'=>0,
                    'status'=>1,
                ]);
            }
        }
    }


    public function assignHospitals(){
        $doctors=\App\Doctor::all();
        foreach ($doctors as $doctor){
            $hospitals=\App\Hospital::inRandomOrder()->limit(3)->get()->pluck('id')->toArray();
            $doctor->hospitals()->sync($hospitals);
        }
    }

    public function assignSpecialites(){
        $doctors=\App\Doctor::all();
        foreach ($doctors as $doctor){
            $specialities=\App\Speciality::inRandomOrder()->limit(5)->get()->pluck('id')->toArray();
            $doctor->specialities()->sync($specialities);
        }
    }



    public function createPatientFamilyMembers(){
        $faker = Faker\Factory::create();
        $patients=\App\Patient::all();
        foreach ($patients as $patient){
            for($i=0;$i<=3;$i++){
                \App\FamilyMember::create([
                    'patient_id'=>$patient->id,
                    'first_name'=>$faker->firstName,
                    'last_name'=>$faker->lastName,
                    'date_of_birth'=>$faker->date(),
                    'gender'=>$faker->randomElement(['male','female']),
                    'status'=>1,
                ]);
            }
        }
    }

    public function createReviews($limit){
        $faker = Faker\Factory::create();
        for ($i=0;$i<=$limit;$i++){
            $review= \App\Review::create([
                'doctor_id'=>\App\Doctor::inRandomOrder()->first()->id,
                'review'=>$faker->sentence(8),
                'patient_id'=>\App\Patient::inRandomOrder()->first()->id,
                'rating'=>rand(0,5),
                'hospital_id'=>0,
                'status'=>1,
                'added_by'=>$faker->randomElement(['Doctor','Patient'])
            ]);

        }
    }

    public function createReplies($limit){
        $faker = Faker\Factory::create();
        for($i=0;$i<=$limit;$i++){
            $review=\App\Review::inRandomOrder()->first();
            $number=$faker->randomElement([1,2,3]);
            if($number==1){
                \App\Reply::create([
                    'review_id'=>$review->id,
                    'doctor_id'=>\App\Doctor::inRandomOrder()->first()->id,
                    'patient_id'=>0,
                    'hospital_id'=>0,
                    'reply'=>$faker->sentence(7),
                    'status'=>1,
                    'created_at'=>$faker->dateTime(),
                ]);
            }elseif($number==2){
                \App\Reply::create([
                    'review_id'=>$review->id,
                    'doctor_id'=>0,
                    'hospital_id'=>0,
                    'patient_id'=>\App\Patient::inRandomOrder()->first()->id,
                    'status'=>1,
                    'reply'=>$faker->sentence(7),

                    'created_at'=>$faker->dateTime()

                ]);
            }elseif($number==3){
                \App\Reply::create([
                    'review_id'=>$review->id,
                    'doctor_id'=>0,
                    'patient_id'=>0,
                    'status'=>1,
                    'reply'=>$faker->sentence(7),
                    'hospital_id'=>\App\Hospital::inRandomOrder()->first()->id,
                    'created_at'=>$faker->dateTime(),
                ]);
            }
        }

    }
    public function createLanguages(){
        $languages = [
            'ab' => 'Abkhazian',
            'aa' => 'Afar',
            'af' => 'Afrikaans',
            'ak' => 'Akan',
            'sq' => 'Albanian',
            'am' => 'Amharic',
            'ar' => 'Arabic',
            'an' => 'Aragonese',
            'hy' => 'Armenian',
            'as' => 'Assamese',
            'av' => 'Avaric',
            'ae' => 'Avestan',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'bm' => 'Bambara',
            'ba' => 'Bashkir',
            'eu' => 'Basque',
            'be' => 'Belarusian',
            'bn' => 'Bengali',
            'bh' => 'Bihari languages',
            'bi' => 'Bislama',
            'bs' => 'Bosnian',
            'br' => 'Breton',
            'bg' => 'Bulgarian',
            'my' => 'Burmese',
            'ca' => 'Catalan, Valencian',
            'km' => 'Central Khmer',
            'ch' => 'Chamorro',
            'ce' => 'Chechen',
            'ny' => 'Chichewa, Chewa, Nyanja',
            'zh' => 'Chinese',
            'cu' => 'Church Slavonic, Old Bulgarian, Old Church Slavonic',
            'cv' => 'Chuvash',
            'kw' => 'Cornish',
            'co' => 'Corsican',
            'cr' => 'Cree',
            'hr' => 'Croatian',
            'cs' => 'Czech',
            'da' => 'Danish',
            'dv' => 'Divehi, Dhivehi, Maldivian',
            'nl' => 'Dutch, Flemish',
            'dz' => 'Dzongkha',
            'en' => 'English',
            'eo' => 'Esperanto',
            'et' => 'Estonian',
            'ee' => 'Ewe',
            'fo' => 'Faroese',
            'fj' => 'Fijian',
            'fi' => 'Finnish',
            'fr' => 'French',
            'ff' => 'Fulah',
            'gd' => 'Gaelic, Scottish Gaelic',
            'gl' => 'Galician',
            'lg' => 'Ganda',
            'ka' => 'Georgian',
            'de' => 'German',
            'ki' => 'Gikuyu, Kikuyu',
            'el' => 'Greek (Modern)',
            'kl' => 'Greenlandic, Kalaallisut',
            'gn' => 'Guarani',
            'gu' => 'Gujarati',
            'ht' => 'Haitian, Haitian Creole',
            'ha' => 'Hausa',
            'he' => 'Hebrew',
            'hz' => 'Herero',
            'hi' => 'Hindi',
            'ho' => 'Hiri Motu',
            'hu' => 'Hungarian',
            'is' => 'Icelandic',
            'io' => 'Ido',
            'ig' => 'Igbo',
            'id' => 'Indonesian',
            'ia' => 'Interlingua (International Auxiliary Language Association)',
            'ie' => 'Interlingue',
            'iu' => 'Inuktitut',
            'ik' => 'Inupiaq',
            'ga' => 'Irish',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'jv' => 'Javanese',
            'kn' => 'Kannada',
            'kr' => 'Kanuri',
            'ks' => 'Kashmiri',
            'kk' => 'Kazakh',
            'rw' => 'Kinyarwanda',
            'kv' => 'Komi',
            'kg' => 'Kongo',
            'ko' => 'Korean',
            'kj' => 'Kwanyama, Kuanyama',
            'ku' => 'Kurdish',
            'ky' => 'Kyrgyz',
            'lo' => 'Lao',
            'la' => 'Latin',
            'lv' => 'Latvian',
            'lb' => 'Letzeburgesch, Luxembourgish',
            'li' => 'Limburgish, Limburgan, Limburger',
            'ln' => 'Lingala',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'mk' => 'Macedonian',
            'mg' => 'Malagasy',
            'ms' => 'Malay',
            'ml' => 'Malayalam',
            'mt' => 'Maltese',
            'gv' => 'Manx',
            'mi' => 'Maori',
            'mr' => 'Marathi',
            'mh' => 'Marshallese',
            'ro' => 'Moldovan, Moldavian, Romanian',
            'mn' => 'Mongolian',
            'na' => 'Nauru',
            'nv' => 'Navajo, Navaho',
            'nd' => 'Northern Ndebele',
            'ng' => 'Ndonga',
            'ne' => 'Nepali',
            'se' => 'Northern Sami',
            'no' => 'Norwegian',
            'nb' => 'Norwegian Bokmål',
            'nn' => 'Norwegian Nynorsk',
            'ii' => 'Nuosu, Sichuan Yi',
            'oc' => 'Occitan (post 1500)',
            'oj' => 'Ojibwa',
            'or' => 'Oriya',
            'om' => 'Oromo',
            'os' => 'Ossetian, Ossetic',
            'pi' => 'Pali',
            'pa' => 'Panjabi, Punjabi',
            'ps' => 'Pashto, Pushto',
            'fa' => 'Persian',
            'pl' => 'Polish',
            'pt' => 'Portuguese',
            'qu' => 'Quechua',
            'rm' => 'Romansh',
            'rn' => 'Rundi',
            'ru' => 'Russian',
            'sm' => 'Samoan',
            'sg' => 'Sango',
            'sa' => 'Sanskrit',
            'sc' => 'Sardinian',
            'sr' => 'Serbian',
            'sn' => 'Shona',
            'sd' => 'Sindhi',
            'si' => 'Sinhala, Sinhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'so' => 'Somali',
            'st' => 'Sotho, Southern',
            'nr' => 'South Ndebele',
            'es' => 'Spanish, Castilian',
            'su' => 'Sundanese',
            'sw' => 'Swahili',
            'ss' => 'Swati',
            'sv' => 'Swedish',
            'tl' => 'Tagalog',
            'ty' => 'Tahitian',
            'tg' => 'Tajik',
            'ta' => 'Tamil',
            'tt' => 'Tatar',
            'te' => 'Telugu',
            'th' => 'Thai',
            'bo' => 'Tibetan',
            'ti' => 'Tigrinya',
            'to' => 'Tonga (Tonga Islands)',
            'ts' => 'Tsonga',
            'tn' => 'Tswana',
            'tr' => 'Turkish',
            'tk' => 'Turkmen',
            'tw' => 'Twi',
            'ug' => 'Uighur, Uyghur',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            've' => 'Venda',
            'vi' => 'Vietnamese',
            'vo' => 'Volap_k',
            'wa' => 'Walloon',
            'cy' => 'Welsh',
            'fy' => 'Western Frisian',
            'wo' => 'Wolof',
            'xh' => 'Xhosa',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'za' => 'Zhuang, Chuang',
            'zu' => 'Zulu'
        ];
        foreach ($languages as $key=>$val){
            \App\Language::create([
                'name'=>$val,
                'code'=>$key
            ]);
        }
    }
    public function doctorLanguages(){
        $doctors=\App\Doctor::all();
        foreach ($doctors as $doctor){
            $languages=\App\Language::inRandomOrder()->limit(3)->get()->pluck('id')->toArray();
            $doctor->languages()->sync($languages);
        }
    }
    public function createCountries(){
//        [ 'AD'=> ['name'=> 'ANDORRA','code'=> '376'] ],
        $countries = [
            [ 'AD'=> ['name'=> 'ANDORRA','code'=> '376'] ],
            [ 'AE'=> ['name'=> 'UNITED ARAB EMIRATES','code'=> '971'] ],
            [ 'AF'=> ['name'=> 'AFGHANISTAN','code'=> '93'] ],
            [ 'AG'=> ['name'=> 'ANTIGUA AND BARBUDA','code'=> '1268'] ],
            [ 'AI'=> ['name'=> 'ANGUILLA','code'=> '1264'] ],
            [ 'AL'=> ['name'=> 'ALBANIA','code'=> '355'] ],
            [ 'AM'=> ['name'=> 'ARMENIA','code'=> '374'] ],
            [ 'AN'=> ['name'=> 'NETHERLANDS ANTILLES','code'=> '599'] ],
            [ 'AO'=> ['name'=> 'ANGOLA','code'=> '244'] ],
            [ 'AQ'=> ['name'=> 'ANTARCTICA','code'=> '672'] ],
            [ 'AR'=> ['name'=> 'ARGENTINA','code'=> '54'] ],
            [ 'AS'=> ['name'=> 'AMERICAN SAMOA','code'=> '1684'] ],
            [ 'AT'=> ['name'=> 'AUSTRIA','code'=> '43'] ],
            [ 'AU'=> ['name'=> 'AUSTRALIA','code'=> '61'] ],
            [ 'AW'=> ['name'=> 'ARUBA','code'=> '297'] ],
            [ 'AZ'=> ['name'=> 'AZERBAIJAN','code'=> '994'] ],
            [ 'BA'=> ['name'=> 'BOSNIA AND HERZEGOVINA','code'=> '387'] ],
            [ 'BB'=> ['name'=> 'BARBADOS','code'=> '1246'] ],
            [ 'BD'=> ['name'=> 'BANGLADESH','code'=> '880'] ],
            [ 'BE'=> ['name'=> 'BELGIUM','code'=> '32'] ],
            [ 'BF'=> ['name'=> 'BURKINA FASO','code'=> '226'] ],
            [ 'BG'=> ['name'=> 'BULGARIA','code'=> '359'] ],
            [ 'BH'=> ['name'=> 'BAHRAIN','code'=> '973'] ],
            [ 'BI'=> ['name'=> 'BURUNDI','code'=> '257'] ],
            [ 'BJ'=> ['name'=> 'BENIN','code'=> '229'] ],
            [ 'BL'=> ['name'=> 'SAINT BARTHELEMY','code'=> '590'] ],
            [ 'BM'=> ['name'=> 'BERMUDA','code'=> '1441'] ],
            [ 'BN'=> ['name'=> 'BRUNEI DARUSSALAM','code'=> '673'] ],
            [ 'BO'=> ['name'=> 'BOLIVIA','code'=> '591'] ],
            [ 'BR'=> ['name'=> 'BRAZIL','code'=> '55'] ],
            [ 'BS'=> ['name'=> 'BAHAMAS','code'=> '1242'] ],
            [ 'BT'=> ['name'=> 'BHUTAN','code'=> '975'] ],
            [ 'BW'=> ['name'=> 'BOTSWANA','code'=> '267'] ],
            [ 'BY'=> ['name'=> 'BELARUS','code'=> '375'] ],
            [ 'BZ'=> ['name'=> 'BELIZE','code'=> '501'] ],
            [ 'CA'=> ['name'=> 'CANADA','code'=> '1'] ],
            [ 'CC'=> ['name'=> 'COCOS (KEELING) ISLANDS','code'=> '61'] ],
            [ 'CD'=> ['name'=> 'CONGO, THE DEMOCRATIC REPUBLIC OF THE','code'=> '243'] ],
            [ 'CF'=> ['name'=> 'CENTRAL AFRICAN REPUBLIC','code'=> '236'] ],
            [ 'CG'=> ['name'=> 'CONGO','code'=> '242'] ],
            [ 'CH'=> ['name'=> 'SWITZERLAND','code'=> '41'] ],
            [ 'CI'=> ['name'=> 'COTE D IVOIRE','code'=> '225'] ],
            [ 'CK'=> ['name'=> 'COOK ISLANDS','code'=> '682'] ],
            [ 'CL'=> ['name'=> 'CHILE','code'=> '56'] ],
            [ 'CM'=> ['name'=> 'CAMEROON','code'=> '237'] ],
            [ 'CN'=> ['name'=> 'CHINA','code'=> '86'] ],
            [ 'CO'=> ['name'=> 'COLOMBIA','code'=> '57'] ],
            [ 'CR'=> ['name'=> 'COSTA RICA','code'=> '506'] ],
            [ 'CU'=> ['name'=> 'CUBA','code'=> '53'] ],
            [ 'CV'=> ['name'=> 'CAPE VERDE','code'=> '238'] ],
            [ 'CX'=> ['name'=> 'CHRISTMAS ISLAND','code'=> '61'] ],
            [ 'CY'=> ['name'=> 'CYPRUS','code'=> '357'] ],
            [ 'CZ'=> ['name'=> 'CZECH REPUBLIC','code'=> '420'] ],
            [ 'DE'=> ['name'=> 'GERMANY','code'=> '49'] ],
            [ 'DJ'=> ['name'=> 'DJIBOUTI','code'=> '253'] ],
            [ 'DK'=> ['name'=> 'DENMARK','code'=> '45'] ],
            [ 'DM'=> ['name'=> 'DOMINICA','code'=> '1767'] ],
            [ 'DO'=> ['name'=> 'DOMINICAN REPUBLIC','code'=> '1809'] ],
            [ 'DZ'=> ['name'=> 'ALGERIA','code'=> '213'] ],
            [ 'EC'=> ['name'=> 'ECUADOR','code'=> '593'] ],
            [ 'EE'=> ['name'=> 'ESTONIA','code'=> '372'] ],
            [ 'EG'=> ['name'=> 'EGYPT','code'=> '20'] ],
            [ 'ER'=> ['name'=> 'ERITREA','code'=> '291'] ],
            [ 'ES'=> ['name'=> 'SPAIN','code'=> '34'] ],
            [ 'ET'=> ['name'=> 'ETHIOPIA','code'=> '251'] ],
            [ 'FI'=> ['name'=> 'FINLAND','code'=> '358'] ],
            [ 'FJ'=> ['name'=> 'FIJI','code'=> '679'] ],
            [ 'FK'=> ['name'=> 'FALKLAND ISLANDS (MALVINAS)','code'=> '500'] ],
            [ 'FM'=> ['name'=> 'MICRONESIA, FEDERATED STATES OF','code'=> '691'] ],
            [ 'FO'=> ['name'=> 'FAROE ISLANDS','code'=> '298'] ],
            [ 'FR'=> ['name'=> 'FRANCE','code'=> '33'] ],
            [ 'GA'=> ['name'=> 'GABON','code'=> '241'] ],
            [ 'GB'=> ['name'=> 'UNITED KINGDOM','code'=> '44'] ],
            [ 'GD'=> ['name'=> 'GRENADA','code'=> '1473'] ],
            [ 'GE'=> ['name'=> 'GEORGIA','code'=> '995'] ],
            [ 'GH'=> ['name'=> 'GHANA','code'=> '233'] ],
            [ 'GI'=> ['name'=> 'GIBRALTAR','code'=> '350'] ],
            [ 'GL'=> ['name'=> 'GREENLAND','code'=> '299'] ],
            [ 'GM'=> ['name'=> 'GAMBIA','code'=> '220'] ],
            [ 'GN'=> ['name'=> 'GUINEA','code'=> '224'] ],
            [ 'GQ'=> ['name'=> 'EQUATORIAL GUINEA','code'=> '240'] ],
            [ 'GR'=> ['name'=> 'GREECE','code'=> '30'] ],
            [ 'GT'=> ['name'=> 'GUATEMALA','code'=> '502'] ],
            [ 'GU'=> ['name'=> 'GUAM','code'=> '1671'] ],
            [ 'GW'=> ['name'=> 'GUINEA-BISSAU','code'=> '245'] ],
            [ 'GY'=> ['name'=> 'GUYANA','code'=> '592'] ],
            [ 'HK'=> ['name'=> 'HONG KONG','code'=> '852'] ],
            [ 'HN'=> ['name'=> 'HONDURAS','code'=> '504'] ],
            [ 'HR'=> ['name'=> 'CROATIA','code'=> '385'] ],
            [ 'HT'=> ['name'=> 'HAITI','code'=> '509'] ],
            [ 'HU'=> ['name'=> 'HUNGARY','code'=> '36'] ],
            [ 'ID'=> ['name'=> 'INDONESIA','code'=> '62'] ],
            [ 'IE'=> ['name'=> 'IRELAND','code'=> '353'] ],
            [ 'IL'=> ['name'=> 'ISRAEL','code'=> '972'] ],
            [ 'IM'=> ['name'=> 'ISLE OF MAN','code'=> '44'] ],
            [ 'IN'=> ['name'=> 'INDIA','code'=> '91'] ],
            [ 'IQ'=> ['name'=> 'IRAQ','code'=> '964'] ],
            [ 'IR'=> ['name'=> 'IRAN, ISLAMIC REPUBLIC OF','code'=> '98'] ],
            [ 'IS'=> ['name'=> 'ICELAND','code'=> '354'] ],
            [ 'IT'=> ['name'=> 'ITALY','code'=> '39'] ],
            [ 'JM'=> ['name'=> 'JAMAICA','code'=> '1876'] ],
            [ 'JO'=> ['name'=> 'JORDAN','code'=> '962'] ],
            [ 'JP'=> ['name'=> 'JAPAN','code'=> '81'] ],
            [ 'KE'=> ['name'=> 'KENYA','code'=> '254'] ],
            [ 'KG'=> ['name'=> 'KYRGYZSTAN','code'=> '996'] ],
            [ 'KH'=> ['name'=> 'CAMBODIA','code'=> '855'] ],
            [ 'KI'=> ['name'=> 'KIRIBATI','code'=> '686'] ],
            [ 'KM'=> ['name'=> 'COMOROS','code'=> '269'] ],
            [ 'KN'=> ['name'=> 'SAINT KITTS AND NEVIS','code'=> '1869'] ],
            [ 'KP'=> ['name'=> 'KOREA DEMOCRATIC PEOPLES REPUBLIC OF','code'=> '850'] ],
            [ 'KR'=> ['name'=> 'KOREA REPUBLIC OF','code'=> '82'] ],
            [ 'KW'=> ['name'=> 'KUWAIT','code'=> '965'] ],
            [ 'KY'=> ['name'=> 'CAYMAN ISLANDS','code'=> '1345'] ],
            [ 'KZ'=> ['name'=> 'KAZAKSTAN','code'=> '7'] ],
            [ 'LA'=> ['name'=> 'LAO PEOPLES DEMOCRATIC REPUBLIC','code'=> '856'] ],
            [ 'LB'=> ['name'=> 'LEBANON','code'=> '961'] ],
            [ 'LC'=> ['name'=> 'SAINT LUCIA','code'=> '1758'] ],
            [ 'LI'=> ['name'=> 'LIECHTENSTEIN','code'=> '423'] ],
            [ 'LK'=> ['name'=> 'SRI LANKA','code'=> '94'] ],
            [ 'LR'=> ['name'=> 'LIBERIA','code'=> '231'] ],
            [ 'LS'=> ['name'=> 'LESOTHO','code'=> '266'] ],
            [ 'LT'=> ['name'=> 'LITHUANIA','code'=> '370'] ],
            [ 'LU'=> ['name'=> 'LUXEMBOURG','code'=> '352'] ],
            [ 'LV'=> ['name'=> 'LATVIA','code'=> '371'] ],
            [ 'LY'=> ['name'=> 'LIBYAN ARAB JAMAHIRIYA','code'=> '218'] ],
            [ 'MA'=> ['name'=> 'MOROCCO','code'=> '212'] ],
            [ 'MC'=> ['name'=> 'MONACO','code'=> '377'] ],
            [ 'MD'=> ['name'=> 'MOLDOVA, REPUBLIC OF','code'=> '373'] ],
            [ 'ME'=> ['name'=> 'MONTENEGRO','code'=> '382'] ],
            [ 'MF'=> ['name'=> 'SAINT MARTIN','code'=> '1599'] ],
            [ 'MG'=> ['name'=> 'MADAGASCAR','code'=> '261'] ],
            [ 'MH'=> ['name'=> 'MARSHALL ISLANDS','code'=> '692'] ],
            [ 'MK'=> ['name'=> 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','code'=> '389'] ],
            [ 'ML'=> ['name'=> 'MALI','code'=> '223'] ],
            [ 'MM'=> ['name'=> 'MYANMAR','code'=> '95'] ],
            [ 'MN'=> ['name'=> 'MONGOLIA','code'=> '976'] ],
            [ 'MO'=> ['name'=> 'MACAU','code'=> '853'] ],
            [ 'MP'=> ['name'=> 'NORTHERN MARIANA ISLANDS','code'=> '1670'] ],
            [ 'MR'=> ['name'=> 'MAURITANIA','code'=> '222'] ],
            [ 'MS'=> ['name'=> 'MONTSERRAT','code'=> '1664'] ],
            [ 'MT'=> ['name'=> 'MALTA','code'=> '356'] ],
            [ 'MU'=> ['name'=> 'MAURITIUS','code'=> '230'] ],
            [ 'MV'=> ['name'=> 'MALDIVES','code'=> '960'] ],
            [ 'MW'=> ['name'=> 'MALAWI','code'=> '265'] ],
            [ 'MX'=> ['name'=> 'MEXICO','code'=> '52'] ],
            [ 'MY'=> ['name'=> 'MALAYSIA','code'=> '60'] ],
            [ 'MZ'=> ['name'=> 'MOZAMBIQUE','code'=> '258'] ],
            [ 'NA'=> ['name'=> 'NAMIBIA','code'=> '264'] ],
            [ 'NC'=> ['name'=> 'NEW CALEDONIA','code'=> '687'] ],
            [ 'NE'=> ['name'=> 'NIGER','code'=> '227'] ],
            [ 'NG'=> ['name'=> 'NIGERIA','code'=> '234'] ],
            [ 'NI'=> ['name'=> 'NICARAGUA','code'=> '505'] ],
            [ 'NL'=> ['name'=> 'NETHERLANDS','code'=> '31'] ],
            [ 'NO'=> ['name'=> 'NORWAY','code'=> '47'] ],
            [ 'NP'=> ['name'=> 'NEPAL','code'=> '977'] ],
            [ 'NR'=> ['name'=> 'NAURU','code'=> '674'] ],
            [ 'NU'=> ['name'=> 'NIUE','code'=> '683'] ],
            [ 'NZ'=> ['name'=> 'NEW ZEALAND','code'=> '64'] ],
            [ 'OM'=> ['name'=> 'OMAN','code'=> '968'] ],
            [ 'PA'=> ['name'=> 'PANAMA','code'=> '507'] ],
            [ 'PE'=> ['name'=> 'PERU','code'=> '51'] ],
            [ 'PF'=> ['name'=> 'FRENCH POLYNESIA','code'=> '689'] ],
            [ 'PG'=> ['name'=> 'PAPUA NEW GUINEA','code'=> '675'] ],
            [ 'PH'=> ['name'=> 'PHILIPPINES','code'=> '63'] ],
            [ 'PK'=> ['name'=> 'PAKISTAN','code'=> '92'] ],
            [ 'PL'=> ['name'=> 'POLAND','code'=> '48'] ],
            [ 'PM'=> ['name'=> 'SAINT PIERRE AND MIQUELON','code'=> '508'] ],
            [ 'PN'=> ['name'=> 'PITCAIRN','code'=> '870'] ],
            [ 'PR'=> ['name'=> 'PUERTO RICO','code'=> '1'] ],
            [ 'PT'=> ['name'=> 'PORTUGAL','code'=> '351'] ],
            [ 'PW'=> ['name'=> 'PALAU','code'=> '680'] ],
            [ 'PY'=> ['name'=> 'PARAGUAY','code'=> '595'] ],
            [ 'QA'=> ['name'=> 'QATAR','code'=> '974'] ],
            [ 'RO'=> ['name'=> 'ROMANIA','code'=> '40'] ],
            [ 'RS'=> ['name'=> 'SERBIA','code'=> '381'] ],
            [ 'RU'=> ['name'=> 'RUSSIAN FEDERATION','code'=> '7'] ],
            [ 'RW'=> ['name'=> 'RWANDA','code'=> '250'] ],
            [ 'SA'=> ['name'=> 'SAUDI ARABIA','code'=> '966'] ],
            [ 'SB'=> ['name'=> 'SOLOMON ISLANDS','code'=> '677'] ],
            [ 'SC'=> ['name'=> 'SEYCHELLES','code'=> '248'] ],
            [ 'SD'=> ['name'=> 'SUDAN','code'=> '249'] ],
            [ 'SE'=> ['name'=> 'SWEDEN','code'=> '46'] ],
            [ 'SG'=> ['name'=> 'SINGAPORE','code'=> '65'] ],
            [ 'SH'=> ['name'=> 'SAINT HELENA','code'=> '290'] ],
            [ 'SI'=> ['name'=> 'SLOVENIA','code'=> '386'] ],
            [ 'SK'=> ['name'=> 'SLOVAKIA','code'=> '421'] ],
            [ 'SL'=> ['name'=> 'SIERRA LEONE','code'=> '232'] ],
            [ 'SM'=> ['name'=> 'SAN MARINO','code'=> '378'] ],
            [ 'SN'=> ['name'=> 'SENEGAL','code'=> '221'] ],
            [ 'SO'=> ['name'=> 'SOMALIA','code'=> '252'] ],
            [ 'SR'=> ['name'=> 'SURINAME','code'=> '597'] ],
            [ 'ST'=> ['name'=> 'SAO TOME AND PRINCIPE','code'=> '239'] ],
            [ 'SV'=> ['name'=> 'EL SALVADOR','code'=> '503'] ],
            [ 'SY'=> ['name'=> 'SYRIAN ARAB REPUBLIC','code'=> '963'] ],
            [ 'SZ'=> ['name'=> 'SWAZILAND','code'=> '268'] ],
            [ 'TC'=> ['name'=> 'TURKS AND CAICOS ISLANDS','code'=> '1649'] ],
            [ 'TD'=> ['name'=> 'CHAD','code'=> '235'] ],
            [ 'TG'=> ['name'=> 'TOGO','code'=> '228'] ],
            [ 'TH'=> ['name'=> 'THAILAND','code'=> '66'] ],
            [ 'TJ'=> ['name'=> 'TAJIKISTAN','code'=> '992'] ],
            [ 'TK'=> ['name'=> 'TOKELAU','code'=> '690'] ],
            [ 'TL'=> ['name'=> 'TIMOR-LESTE','code'=> '670'] ],
            [ 'TM'=> ['name'=> 'TURKMENISTAN','code'=> '993'] ],
            [ 'TN'=> ['name'=> 'TUNISIA','code'=> '216'] ],
            [ 'TO'=> ['name'=> 'TONGA','code'=> '676'] ],
            [ 'TR'=> ['name'=> 'TURKEY','code'=> '90'] ],
            [ 'TT'=> ['name'=> 'TRINIDAD AND TOBAGO','code'=> '1868'] ],
            [ 'TV'=> ['name'=> 'TUVALU','code'=> '688'] ],
            [ 'TW'=> ['name'=> 'TAIWAN, PROVINCE OF CHINA','code'=> '886'] ],
            [ 'TZ'=> ['name'=> 'TANZANIA, UNITED REPUBLIC OF','code'=> '255'] ],
            [ 'UA'=> ['name'=> 'UKRAINE','code'=> '380'] ],
            [ 'UG'=> ['name'=> 'UGANDA','code'=> '256'] ],
            [ 'US'=> ['name'=> 'UNITED STATES','code'=> '1'] ],
            [ 'UY'=> ['name'=> 'URUGUAY','code'=> '598'] ],
            [ 'UZ'=> ['name'=> 'UZBEKISTAN','code'=> '998'] ],
            [ 'VA'=> ['name'=> 'HOLY SEE (VATICAN CITY STATE)','code'=> '39'] ],
            [ 'VC'=> ['name'=> 'SAINT VINCENT AND THE GRENADINES','code'=> '1784'] ],
            [ 'VE'=> ['name'=> 'VENEZUELA','code'=> '58'] ],
            [ 'VG'=> ['name'=> 'VIRGIN ISLANDS, BRITISH','code'=> '1284'] ],
            [ 'VI'=> ['name'=> 'VIRGIN ISLANDS, U.S.','code'=> '1340'] ],
            [ 'VN'=> ['name'=> 'VIET NAM','code'=> '84'] ],
            [ 'VU'=> ['name'=> 'VANUATU','code'=> '678'] ],
            [ 'WF'=> ['name'=> 'WALLIS AND FUTUNA','code'=> '681'] ],
            [ 'WS'=> ['name'=> 'SAMOA','code'=> '685'] ],
            [ 'XK'=> ['name'=> 'KOSOVO','code'=> '381'] ],
            [ 'YE'=> ['name'=> 'YEMEN','code'=> '967'] ],
            [ 'YT'=> ['name'=> 'MAYOTTE','code'=> '262'] ],
            [ 'ZA'=> ['name'=> 'SOUTH AFRICA','code'=> '27'] ],
            [ 'ZM'=> ['name'=> 'ZAMBIA','code'=> '260'] ],
            [ 'ZW'=> ['name'=> 'ZIMBABWE','code'=> '263'] ]
        ];

        foreach ($countries as $key=>$val){
            $country=key($val);
            \App\Country::create([
                'code'=>strtoupper($country),
                'name'=>ucfirst($val[$country]['name']),
                'phone_code'=>'+'.$val[$country]['code'],
                'slug'=>str_slug($val[$country]['name'])
            ]);
        }
    }

    public function createUAECities(){
        $cities=[
            'Umm al Qaywayn','Ra’s al Khaymah','Muzayri','Khawr Fakkan','Dubai','Diba','Sharjah','Ar Ruways','Al Fujayrah','Al ‘Ayn','Ajman','Adh Dhayd','Abu Dhabi',
        ];

        $country=\App\Country::where('code','AE')->first();
        foreach ($cities as $city){
            \App\City::create([
                'country_id'=>$country->id,
                'name'=>$city
            ]);
        }


    }




}
