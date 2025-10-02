<?php

namespace App\Domains\Erp\Services;

use _PHPStan_ce0aaf2bf\Nette\Neon\Exception;
use App\Domains\CountryCodes\Services\CountryCodeService;
use Illuminate\Support\Facades\Cache;

class MapperService
{
    private array $countries = [];

    public function __construct(private readonly CountryCodeService $countryCodeService,)
    {
//        Cache::forget("countryCodes");
        $countries = $this->countryCodeService->get();
        foreach ($countries as $country) {
            $this->countries[$country->getErpId()] = [
                "code" => $country->getCode(),
                "id" => $country->getId(),
            ];
        }
    }

    public function mapCustomer(array $erpCustomer): array
    {
        return [
            'erp_id' => $erpCustomer['CUSTCODE'],
            'name' => $erpCustomer['CUSTNAME'],
            'phone' => $erpCustomer['CUSTPHONE'],
            'activity' => $erpCustomer['JOBDESCR'],
            'type_id' => $this->mapActivity($erpCustomer['JOBTYPE']),
//            'address' => $erpCustomer['CUSTADDRESS'],
//            'vat' => $erpCustomer['CUSTAFM'],
//            'doy' => $this->mapDOY($erpCustomer['CUSTDOY']),
            'country_id' => $this->countries[$erpCustomer['CUSTCOUNTRY']]["id"],
            'city' => $erpCustomer['CUSTCITY'],
        ];
    }

    public function mapActivity(string $activity): ?int
    {
        return null;
//        return match ($activity) {
//            "ΙΔΙΩΤΗΣ" => 1,
//            "ΕΜΠΟΡΟΣ" => 1,
//            default => throw new Exception("Activity: $activity not mapped")
//        };
    }

    public function mapDOY(string $doy): ?int
    {
        return null;
//        return match ($doy) {
//            "ΙΔΙΩΤΗΣ" => 1,
//            default => throw new Exception("Doy: $doy not mapped")
//        };
    }

    public function mapCountry(int $countryCode): ?string
    {
        return match ($countryCode) {
            23 => "US",     // USA
            1000 => "GR",   // ΕΛΛΑΣ (Greece)
            1001 => "AT",   // AUSTRIA
            1002 => "BE",   // BELGIUM
            1003 => "BG",   // BULGARIA
            1004 => "FR",   // FRANCE
            1005 => "DE",   // GERMANY
            1006 => "DK",   // DENMARK
            1007 => "EE",   // ESTHONIA (Estonia)
            1008 => "GB",   // GREAT BRITAIN
            1009 => "IE",   // IRELAND
            1010 => "ES",   // SPAIN
            1011 => "IT",   // ITALY
            1012 => "NL",   // NETHERLANDS
            1013 => "HR",   // CROATIA
            1014 => "CY",   // CYPRUS
            1015 => "LV",   // ΛΕΤΟΝΙΑ (Latvia)
            1016 => "LT",   // LITHUANIA
            1017 => "LU",   // LUXEMBOURG
            1018 => "MT",   // MALTA
            1019 => "HU",   // HUNGARY
            1020 => "PL",   // POLAND
            1021 => "PT",   // PORTUGAL
            1022 => "RO",   // ROMANIA
            1023 => "SK",   // SLOVAKIA
            1024 => "SI",   // SLOVENIA
            1025 => "SE",   // ΣΟΥΗΔΙΑ (Sweden)
            1026 => "CZ",   // ΤΣΕΧΙΑ (Czech Republic)
            1027 => "FI",   // FINLAND
            1028 => "TR",   // TURKIYE
            1029 => "RS",   // SERBIA
            1030 => "AL",   // ALBANIA
            1031 => "EG",   // EGYPT
            1032 => "ZA",   // S. AFRICA
            1034 => "LC",   // ΑΓΙΑ ΛΟΥΚΙΑ (Saint Lucia)
            1035 => "BL",   // ΑΓΙΟΣ ΒΑΡΘΟΛΟΜΑΙΟΣ (Saint Barthélemy)
            1036 => "VC",   // ΑΓΙΟΣ ΒΙΚΕΝΤΙΟΣ ΚΑΙ ΓΡΕΝΑΔΙΝΕΣ (Saint Vincent and the Grenadines)
            1037 => "ST",   // ΑΓΙΟΣ ΘΩΜΑΣ ΚΑΙ ΠΡΙΓΚΙΠΑΣ (São Tomé and Príncipe)
            1038 => "SM",   // ΑΓΙΟΣ ΜΑΡΙΝΟΣ (San Marino)
            1039 => "MF",   // ΑΓΙΟΣ ΜΑΡΤΙΝΟΣ (ΓΑΛΛΙΚΟ ΜΕΡΟΣ) (Saint Martin - French part)
            1040 => "SX",   // ΑΓΙΟΣ ΜΑΡΤΙΝΟΣ (ΟΛΛΑΝΔΙΚΟ ΜΕΡΟΣ) (Sint Maarten - Dutch part)
            1041 => "KN",   // ΑΓΙΟΣ ΧΡΙΣΤΟΦΟΡΟΣ ΚΑΙ ΝΕΒΙΣ (Saint Kitts and Nevis)
            1042 => "AZ",   // ΑΖΕΡΜΠΑΪΤΖΑΝ (Azerbaijan)
            1044 => "ET",   // ΑΙΘΙΟΠΙΑ (Ethiopia)
            1045 => "HT",   // ΑΪΤΗ (Haiti)
            1046 => "CI",   // ΑΚΤΗ ΕΛΕΦΑΝΤΟΣΤΟΥ (Ivory Coast)
            1048 => "DZ",   // ALGERIA
            1049 => "VI",   // ΑΜΕΡΙΚΑΝΙΚΕΣ ΠΑΡΘΕΝΟΙ ΝΗΣΟΙ (U.S. Virgin Islands)
            1050 => "AS",   // ΑΜΕΡΙΚΑΝΙΚΗ ΣΑΜΟΑ (American Samoa)
            1051 => "TL",   // ΑΝΑΤΟΛΙΚΟ ΤΙΜΟΡ (East Timor)
            1052 => "AO",   // ΑΝΓΚΟΛΑ (Angola)
            1053 => "AI",   // ΑΝΓΚΟΥΙΛΑ (Anguilla)
            1054 => "AD",   // ΑΝΔΟΡΡΑ (Andorra)
            1055 => "AG",   // ΑΝΤΙΓΚΟΥΑ ΚΑΙ ΜΠΑΡΜΠΟΥΝΤΑ (Antigua and Barbuda)
            1056 => "AR",   // ΑΡΓΕΝΤΙΝΗ (Argentina)
            1057 => "AM",   // ΑΡΜΕΝΙΑ (Armenia)
            1058 => "AW",   // ΑΡΟΥΜΠΑ (Aruba)
            1059 => "AU",   // ΑΥΣΤΡΑΛΙΑ (Australia)
            1060 => "AF",   // ΑΦΓΑΝΙΣΤΑΝ (Afghanistan)
            1061 => "VU",   // ΒΑΝΟΥΑΤΟΥ (Vanuatu)
            1062 => "VA",   // ΒΑΤΙΚΑΝΟ (Vatican City)
            1063 => "VE",   // ΒΕΝΕΖΟΥΕΛΑ (Venezuela)
            1064 => "BM",   // ΒΕΡΜΟΥΔΕΣ (Bermuda)
            1065 => "VN",   // VIETNAM
            1066 => "BO",   // ΒΟΛΙΒΙΑ (Bolivia)
            1067 => "KP",   // ΒΟΡΕΙΑ ΚΟΡΕΑ (North Korea)
            1068 => "MK",   // REP. OF NORTH MACEDONIA
            1069 => "MP",   // ΒΟΡΕΙΕΣ ΜΑΡΙΑΝΕΣ ΝΗΣΟΙ (Northern Mariana Islands)
            1070 => "BA",   // ΒΟΣΝΙΑ ΚΑΙ ΕΡΖΕΓΟΒΙΝΗ (Bosnia and Herzegovina)
            1071 => "BR",   // BRASIL (Brazil)
            1072 => "VG",   // ΒΡΕΤΑΝΙΚΕΣ ΠΑΡΘΕΝΟΙ ΝΗΣΟΙ (British Virgin Islands)
            1073 => "IO",   // ΒΡΕΤΑΝΙΚΟ ΕΔΑΦΟΣ ΙΝΔΙΚΟΥ ΩΚΕΑΝΟΥ (British Indian Ocean Territory)
            1074 => "TF",   // ΓΑΛΛΙΚΑ ΝΟΤΙΑ ΕΔΑΦΗ (French Southern Territories)
            1075 => "GF",   // ΓΑΛΛΙΚΗ ΓΟΥΙΑΝΑ (French Guiana)
            1076 => "PF",   // ΓΑΛΛΙΚΗ ΠΟΛΥΝΗΣΙΑ (French Polynesia)
            1077 => "GE",   // GEORGIA
            1078 => "GI",   // ΓΙΒΡΑΛΤΑΡ (Gibraltar)
            1079 => "GM",   // ΓΚΑΜΠΙΑ (Gambia)
            1080 => "GA",   // ΓΚΑΜΠΟΝ (Gabon)
            1081 => "GH",   // ΓΚΑΝΑ (Ghana)
            1082 => "GG",   // ΓΚΕΡΝΣΕΪ (Guernsey)
            1083 => "GU",   // ΓΚΟΥΑΜ (Guam)
            1084 => "GP",   // ΓΟΥΑΔΕΛΟΥΠΗ (Guadeloupe)
            1085 => "GT",   // ΓΟΥΑΤΕΜΑΛΑ (Guatemala)
            1086 => "GY",   // ΓΟΥΙΑΝΑ (Guyana)
            1087 => "GN",   // ΓΟΥΙΝΕΑ (Guinea)
            1088 => "GW",   // ΓΟΥΙΝΕΑ-ΜΠΙΣΣΑΟΥ (Guinea-Bissau)
            1089 => "GD",   // ΓΡΕΝΑΔΑ (Grenada)
            1090 => "GL",   // ΓΡΟΙΛΑΝΔΙΑ (Greenland)
            1091 => "EH",   // ΔΗΜΟΚΡΑΤΙΑ ΤΗΣ ΣΑΧΑΡΑΣ (Western Sahara)
            1092 => "CG",   // ΔΗΜΟΚΡΑΤΙΑ ΤΟΥ ΚΟΝΓΚΟ (Republic of the Congo)
            1093 => "DM",   // ΔΟΜΙΝΙΚΑ (Dominica)
            1094 => "DO",   // ΔΟΜΙΝΙΚΑΝΗ ΔΗΜΟΚΡΑΤΙΑ (Dominican Republic)
            1095 => "SV",   // ΕΛ ΣΑΛΒΑΔΟΡ (El Salvador)
            1096 => "CH",   // SWITZERLAND
            1097 => "ER",   // ΕΡΥΘΡΑΙΑ (Eritrea)
            1098 => "SZ",   // ΕΣΟΥΑΤΙΝΙ (Eswatini)
            1099 => "ZM",   // ΖΑΜΠΙΑ (Zambia)
            1100 => "ZW",   // ΖΙΜΠΑΜΠΟΥΕ (Zimbabwe)
            1101 => "AE",   // UAE
            1102 => "JP",   // JAPAN
            1103 => "IN",   // INDIA
            1104 => "ID",   // INDONESIA
            1105 => "JO",   // JORDAN
            1106 => "IQ",   // IRAQ
            1107 => "IR",   // IRAN
            1108 => "GQ",   // ΙΣΗΜΕΡΙΝΗ ΓΟΥΙΝΕΑ (Equatorial Guinea)
            1109 => "EC",   // ΙΣΗΜΕΡΙΝΟΣ (Ecuador)
            1110 => "IS",   // ICELAND
            1111 => "IL",   // ISRAEL
            1112 => "KZ",   // KAZAKSTAN
            1113 => "CM",   // ΚΑΜΕΡΟΥΝ (Cameroon)
            1114 => "KH",   // ΚΑΜΠΟΤΖΗ (Cambodia)
            1115 => "CA",   // CANADA
            1116 => "QA",   // ΚΑΤΑΡ (Qatar)
            1117 => "CF",   // ΚΕΝΤΡΟΑΦΡΙΚΑΝΙΚΗ ΔΗΜΟΚΡΑΤΙΑ (Central African Republic)
            1118 => "KE",   // ΚΕΝΥΑ (Kenya)
            1119 => "CN",   // CHINA
            1120 => "KG",   // ΚΙΡΓΙΖΙΑ (Kyrgyzstan)
            1121 => "KI",   // ΚΙΡΙΜΠΑΤΙ (Kiribati)
            1122 => "CO",   // ΚΟΛΟΜΒΙΑ (Colombia)
            1123 => "KM",   // ΚΟΜΟΡΕΣ (Comoros)
            1124 => "CR",   // ΚΟΣΤΑ ΡΙΚΑ (Costa Rica)
            1125 => "CU",   // ΚΟΥΒΑ (Cuba)
            1126 => "KW",   // KUWAIT
            1127 => "CW",   // ΚΟΥΡΑΣΑΟ (Curaçao)
            1128 => "PS",   // ΚΡΑΤΟΣ ΤΗΣ ΠΑΛΑΙΣΤΙΝΗΣ (State of Palestine)
            1129 => "CD",   // ΛΑΪΚΗ ΔΗΜΟΚΡΑΤΙΑ ΤΟΥ ΚΟΝΓΚΟ (Democratic Republic of the Congo)
            1130 => "LA",   // ΛΑΪΚΗ ΔΗΜΟΚΡΑΤΙΑ ΤΟΥ ΛΑΟΣ (Laos)
            1131 => "LS",   // ΛΕΣΟΤΟ (Lesotho)
            1132 => "BY",   // ΛΕΥΚΟΡΩΣΙΑ (Belarus)
            1133 => "LB",   // LEBANON
            1134 => "LR",   // ΛΙΒΕΡΙΑ (Liberia)
            1135 => "LY",   // ΛΙΒΥΗ (Libya)
            1136 => "LI",   // ΛΙΧΤΕΝΣΤΑΪΝ (Liechtenstein)
            1137 => "YT",   // ΜΑΓΙΟΤ (Mayotte)
            1138 => "MG",   // ΜΑΔΑΓΑΣΚΑΡΗ (Madagascar)
            1139 => "MO",   // ΜΑΚΑΟΥ (Macau)
            1140 => "MY",   // MALAYSIA
            1141 => "MW",   // ΜΑΛΑΟΥΙ (Malawi)
            1142 => "MV",   // ΜΑΛΔΙΒΕΣ (Maldives)
            1143 => "ML",   // ΜΑΛΙ (Mali)
            1144 => "MA",   // ΜΑΡΟΚΟ (Morocco)
            1145 => "MQ",   // ΜΑΡΤΙΝΙΚΑ (Martinique)
            1146 => "MU",   // ΜΑΥΡΙΚΙΟΣ (Mauritius)
            1147 => "MR",   // ΜΑΥΡΙΤΑΝΙΑ (Mauritania)
            1148 => "ME",   // ΜΑΥΡΟΒΟΥΝΙΟ (Montenegro)
            1149 => "MX",   // ΜΕΞΙΚΟ (Mexico)
            1150 => "MM",   // ΜΙΑΝΜΑΡ (Myanmar)
            1151 => "MN",   // ΜΟΓΓΟΛΙΑ (Mongolia)
            1152 => "MZ",   // ΜΟΖΑΜΒΙΚΗ (Mozambique)
            1153 => "MD",   // ΜΟΛΔΑΒΙΑ (Moldova)
            1154 => "MC",   // ΜΟΝΑΚΟ (Monaco)
            1155 => "MS",   // ΜΟΝΤΣΕΡΡΑΤ (Montserrat)
            1156 => "BD",   // ΜΠΑΝΓΚΛΑΝΤΕΣ (Bangladesh)
            1157 => "BB",   // ΜΠΑΡΜΠΑΝΤΟΣ (Barbados)
            1158 => "BS",   // ΜΠΑΧΑΜΕΣ (Bahamas)
            1159 => "BH",   // ΜΠΑΧΡΕΙΝ (Bahrain)
            1160 => "BZ",   // ΜΠΕΛΙΖ (Belize)
            1161 => "BJ",   // ΜΠΕΝΙΝ (Benin)
            1162 => "BQ",   // ΜΠΟΝΑΙΡ, ΑΓΙΟΣ ΕΥΣΤΑΘΙΟΣ, ΣΑΜΠΑ (Bonaire, Sint Eustatius and Saba)
            1163 => "BW",   // ΜΠΟΤΣΟΥΑΝΑ (Botswana)
            1164 => "BF",   // ΜΠΟΥΡΚΙΝΑ ΦΑΣΟ (Burkina Faso)
            1165 => "BI",   // ΜΠΟΥΡΟΥΝΤΙ (Burundi)
            1166 => "BT",   // ΜΠΟΥΤΑΝ (Bhutan)
            1167 => "BN",   // ΜΠΡΟΥΝΕΙ ΝΤΑΡΟΥΣΑΛΑΜ (Brunei)
            1168 => "NA",   // NAMIBIA
            1169 => "NR",   // ΝΑΟΥΡΟΥ (Nauru)
            1170 => "NZ",   // NEW ZEALAND
            1171 => "NC",   // ΝΕΑ ΚΑΛΗΔΟΝΙΑ (New Caledonia)
            1172 => "NP",   // ΝΕΠΑΛ (Nepal)
            1173 => "BV",   // ΝΗΣΙ ΜΠΟΥΒΕ (Bouvet Island)
            1174 => "NF",   // ΝΗΣΙ ΝΟΡΦΟΛΚ (Norfolk Island)
            1175 => "KY",   // ΝΗΣΙΑ ΚΕΙΜΑΝ (Cayman Islands)
            1176 => "CC",   // ΝΗΣΙΑ ΚΟΚΟΣ (Cocos Islands)
            1177 => "SH",   // ΝΗΣΟΙ ΑΓΙΑΣ ΕΛΕΝΗΣ, ΑΝΑΛΗΨΕΩΣ ΚΑΙ ΤΡΙΣΤΑΝ ΝΤΑ ΚΟΥΝ (Saint Helena, Ascension and Tristan da Cunha)
            1178 => "PM",   // ΝΗΣΟΙ ΑΓΙΟΥ ΠΕΤΡΟΥ ΚΑΙ ΜΙΧΑΗΛ (Saint Pierre and Miquelon)
            1179 => "CK",   // ΝΗΣΟΙ ΚΟΥΚ (Cook Islands)
            1180 => "MH",   // ΝΗΣΟΙ ΜΑΡΣΑΛ (Marshall Islands)
            1181 => "GS",   // ΝΗΣΟΙ ΝΟΤΙΑ ΓΕΩΡΓΙΑ ΚΑΙ ΝΟΤΙΕΣ ΣΑΝΤΟΥΙΤΣ (South Georgia and the South Sandwich Islands)
            1182 => "PN",   // ΝΗΣΟΙ ΠΙΤΚΑΙΡΝ (Pitcairn Islands)
            1183 => "SB",   // ΝΗΣΟΙ ΣΟΛΟΜΩΝΤΑ (Solomon Islands)
            1184 => "FO",   // ΝΗΣΟΙ ΦΕΡΟΕΣ (Faroe Islands)
            1185 => "FK",   // ΝΗΣΟΙ ΦΩΚΛΑΝΤ (Falkland Islands)
            1186 => "FK",   // ΝΗΣΟΙ ΦΩΚΛΑΝΤ (ΜΑΛΒΙΝΕΣ) (Falkland Islands - Malvinas)
            1187 => "HM",   // ΝΗΣΟΙ ΧΕΡΝΤ ΚΑΙ ΜΑΚ ΝΤΟΛΑΝΤ (Heard Island and McDonald Islands)
            1188 => "IM",   // ΝΗΣΟΣ ΜΑΝ (Isle of Man)
            1189 => "CX",   // ΝΗΣΟΣ ΤΩΝ ΧΡΙΣΤΟΥΓΕΝΝΩΝ (Christmas Island)
            1190 => "NE",   // ΝΙΓΗΡΑΣ (Niger)
            1191 => "NG",   // ΝΙΓΗΡΙΑ (Nigeria)
            1192 => "NI",   // ΝΙΚΑΡΑΓΟΥΑ (Nicaragua)
            1193 => "NU",   // ΝΙΟΥΕ (Niue)
            1194 => "NO",   // NORWAY
            1195 => "KR",   // S. KOREA
            1196 => "SS",   // ΝΟΤΙΟ ΣΟΥΔΑΝ (South Sudan)
            1197 => "NL",   // ΟΛΛΑΝΔΙΑ (Netherlands)
            1198 => "OM",   // OMAN
            1199 => "FM",   // ΟΜΟΣΠΟΝΔΕΣ ΠΟΛΙΤΕΙΕΣ ΤΗΣ ΜΙΚΡΟΝΗΣΙΑΣ (Federated States of Micronesia)
            1200 => "HN",   // ΟΝΔΟΥΡΑ (Honduras)
            1201 => "UG",   // ΟΥΓΚΑΝΤΑ (Uganda)
            1202 => "UZ",   // ΟΥΖΜΠΕΚΙΣΤΑΝ (Uzbekistan)
            1203 => "UA",   // UKRAINA
            1204 => "UY",   // ΟΥΡΟΥΓΟΥΑΗ (Uruguay)
            1205 => "WF",   // ΟΥΩΛΙΣ ΚΑΙ ΦΟΥΤΟΥΝΑ (Wallis and Futuna)
            1206 => "PK",   // PAKISTAN
            1207 => "PW",   // ΠΑΛΑΟΥ (Palau)
            1208 => "PA",   // ΠΑΝΑΜΑΣ (Panama)
            1209 => "PG",   // ΠΑΠΟΥΑ ΝΕΑ ΓΟΥΙΝΕΑ (Papua New Guinea)
            1210 => "PY",   // ΠΑΡΑΓΟΥΑΗ (Paraguay)
            1211 => "PE",   // PERU
            1212 => "PR",   // ΠΟΥΕΡΤΟ ΡΙΚΟ (Puerto Rico)
            1213 => "CV",   // ΠΡΑΣΙΝΟ ΑΚΡΩΤΗΡΙΟ (Cape Verde)
            1214 => "RE",   // ΡΕΫΝΙΟΝ (Réunion)
            1215 => "RW",   // ΡΟΥΑΝΤΑ (Rwanda)
            1216 => "RU",   // RUSSIA
            1217 => "WS",   // ΣΑΜΟΑ (Samoa)
            1218 => "SA",   // SAUDI ARABIA
            1219 => "SJ",   // ΣΒΑΛΜΠΑΡΝΤ ΚΑΙ ΓΙΑΝ ΜΑΓΕΝ (Svalbard and Jan Mayen)
            1220 => "SN",   // ΣΕΝΕΓΑΛΗ (Senegal)
            1221 => "SC",   // ΣΕΫΧΕΛΛΕΣ (Seychelles)
            1222 => "SG",   // SINGAPORE
            1223 => "SL",   // ΣΙΕΡΑ ΛΕΟΝΕ (Sierra Leone)
            1224 => "SO",   // ΣΟΜΑΛΙΑ (Somalia)
            1225 => "SD",   // ΣΟΥΔΑΝ (Sudan)
            1226 => "SR",   // ΣΟΥΡΙΝΑΜ (Suriname)
            1227 => "LK",   // ΣΡΙ ΛΑΝΚΑ (Sri Lanka)
            1228 => "SY",   // ΣΥΡΙΑ (Syria)
            1229 => "TW",   // ΤΑΪΒΑΝ (Taiwan)
            1230 => "TH",   // ΤΑΪΛΑΝΔΗ (Thailand)
            1231 => "TZ",   // ΤΑΝΖΑΝΙΑ (Tanzania)
            1232 => "TJ",   // ΤΑΤΖΙΚΙΣΤΑΝ (Tajikistan)
            1233 => "TC",   // ΤΕΡΚΣ ΚΑΙ ΚΕΙΚΟΣ (Turks and Caicos Islands)
            1234 => "JM",   // ΤΖΑΜΑΙΚΑ (Jamaica)
            1235 => "JE",   // ΤΖΕΡΣΕΪ (Jersey)
            1236 => "DJ",   // ΤΖΙΜΠΟΥΤΙ (Djibouti)
            1237 => "TG",   // TOGO
            1238 => "TK",   // ΤΟΚΕΛΑΟΥ (Tokelau)
            1239 => "TO",   // ΤΟΝΓΚΑ (Tonga)
            1240 => "TV",   // ΤΟΥΒΑΛΟΥ (Tuvalu)
            1241 => "TM",   // ΤΟΥΡΚΜΕΝΙΣΤΑΝ (Turkmenistan)
            1242 => "TT",   // ΤΡΙΝΙΝΤΑΝΤ ΚΑΙ ΤΟΜΠΑΓΚΟ (Trinidad and Tobago)
            1243 => "TD",   // ΤΣΑΝΤ (Chad)
            1244 => "TN",   // ΤΥΝΗΣΙΑ (Tunisia)
            1245 => "YE",   // ΥΕΜΕΝΗ (Yemen)
            1246 => "PH",   // ΦΙΛΙΠΠΙΝΕΣ (Philippines)
            1247 => "FJ",   // ΦΙΤΖΙ (Fiji)
            1248 => "CL",   // ΧΙΛΗ (Chile)
            1249 => "HK",   // HONG KONG
            default => null
        };
    }

    public function mapItemsFromRows(array $fields, array $rows): array
    {
        $fieldNames = array_column($fields, 'name');

        $mapped = [];
        foreach ($rows as $row) {
            // Συνδέουμε κάθε value με το αντίστοιχο field name
            $assoc = array_combine($fieldNames, $row);

            $mapped[] = $this->mapItem($assoc);
        }

        return $mapped;
    }
    public function mapItem(array $erpItem): array
    {
        return [
            'erp_id'         => $erpItem['ITEM.CODE'] ?? null,
            'name'           => $erpItem['ITEM.NAME'] ?? null,
            'mtr_category'   => $erpItem['ITEM.MTRCATEGORY'] ?? null,
            'price_wholesale'=> isset($erpItem['ITEM.PRICEW']) ? (float)$erpItem['ITEM.PRICEW'] : null,
            'price_retail'   => isset($erpItem['ITEM.PRICER']) ? (float)$erpItem['ITEM.PRICER'] : null,
            'brand'          => $erpItem['ITEM.MTRMARK'] ?? null,
            'mtr_model'      => $erpItem['ITEM.MTRMODEL'] ?? null,
            'image_path'     => $erpItem['ITEM.MTRL_ITEDOCDATA_SODATA'] ?? null,
        ];
    }

//    public function mapCustomerFromRows(array $fields, array $rows): array
//    {
//        $fieldNames = array_column($fields, 'name');
//
//        $mapped = [];
//        foreach ($rows as $row) {
//            // Συνδέουμε κάθε value με το αντίστοιχο field name
//            $assoc = array_combine($fieldNames, $row);
//
//            $mapped[] = $this->mapCustomerFromBrowserInfo($assoc);
//        }
//
//        return $mapped;
//    }
//    public function mapCustomerFromBrowserInfo(array $erpCustomer): array
//    {
//        return [
//            'erp_id'         => $erpItem['CUSTOMER.CODE'] ?? null,
//            'name'           => $erpItem['CUSTOMER.NAME'] ?? null,
//            'afm'            => $erpItem['CUSTOMER.AFM'] ?? null,
//            'address'        => $erpItem['CUSTOMER.ADDRESS'] ?? null,
//            'district'       => $erpItem['CUSTOMER.DISTRICT'] ?? null,
//            'zip'            => $erpItem['CUSTOMER.ZIP'] ?? null,
//            'phone'          => $erpItem['CUSTOMER.PHONE01'] ?? null,
//            'website'        => $erpItem['CUSTOMER.WEBPAGE'] ?? null,
//            'email'          => $erpItem['CUSTOMER.EMAIL'] ?? null,
//        ];
//    }
}
