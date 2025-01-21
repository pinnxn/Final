<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $realCompanies = [
            ['name' => 'Apple Inc.', 'address' => '1 Apple Park Way, Cupertino, CA 95014, USA', 'phone' => '1-800-692-7753', 'email' => 'support@apple.com', 'website' => 'https://www.apple.com', 'logo' => 'https://www.apple.com/ac/structured-data/images/open_graph_logo.png'],
            ['name' => 'Microsoft Corporation', 'address' => 'One Microsoft Way, Redmond, WA 98052, USA', 'phone' => '1-800-642-7676', 'email' => 'support@microsoft.com', 'website' => 'https://www.microsoft.com', 'logo' => 'https://www.microsoft.com/en-us/about/images/social-fb-thumb.jpg'],
            ['name' => 'Amazon.com, Inc.', 'address' => '410 Terry Ave N, Seattle, WA 98109, USA', 'phone' => '1-800-201-7575', 'email' => 'support@amazon.com', 'website' => 'https://www.amazon.com', 'logo' => 'https://www.amazon.com/favicon.ico'],
            ['name' => 'Google LLC', 'address' => '1600 Amphitheatre Parkway, Mountain View, CA 94043, USA', 'phone' => '1-800-275-2273', 'email' => 'support@google.com', 'website' => 'https://www.google.com', 'logo' => 'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png'],
            ['name' => 'Facebook, Inc.', 'address' => '1 Hacker Way, Menlo Park, CA 94025, USA', 'phone' => '1-800-933-8474', 'email' => 'support@facebook.com', 'website' => 'https://www.facebook.com', 'logo' => 'https://www.facebook.com/images/fb_icon_325x325.png'],
            ['name' => 'Tesla, Inc.', 'address' => '3500 Deer Creek Road, Palo Alto, CA 94304, USA', 'phone' => '1-800-833-5427', 'email' => 'support@tesla.com', 'website' => 'https://www.tesla.com', 'logo' => 'https://www.tesla.com/tesla_theme/assets/img/meta-logo.png'],
            ['name' => 'Netflix, Inc.', 'address' => '100 Winchester Circle, Los Gatos, CA 95032, USA', 'phone' => '1-800-833-5427', 'email' => 'support@netflix.com', 'website' => 'https://www.netflix.com', 'logo' => 'https://www.netflix.com/favicon.ico'],
            ['name' => 'Intel Corporation', 'address' => '2200 Mission College Blvd, Santa Clara, CA 95054, USA', 'phone' => '1-800-546-4335', 'email' => 'support@intel.com', 'website' => 'https://www.intel.com', 'logo' => 'https://www.intel.com/content/dam/www/public/us/en/images/logos/logo-intel.png'],
            ['name' => 'IBM Corporation', 'address' => '1 New Orchard Road, Armonk, NY 10504, USA', 'phone' => '1-800-426-1968', 'email' => 'support@ibm.com', 'website' => 'https://www.ibm.com', 'logo' => 'https://www.ibm.com/favicon.ico'],
            ['name' => 'Oracle Corporation', 'address' => '500 Oracle Parkway, Redwood Shores, CA 94065, USA', 'phone' => '1-800-633-0738', 'email' => 'support@oracle.com', 'website' => 'https://www.oracle.com', 'logo' => 'https://www.oracle.com/favicon.ico'],
            ['name' => 'Samsung Electronics Co., Ltd.', 'address' => '129, Samsung-ro, Yeongtong-gu, Goyang-si, Gyeonggi-do, 10980, South Korea', 'phone' => '82-2-2053-1114', 'email' => 'support@samsung.com', 'website' => 'https://www.samsung.com', 'logo' => 'https://www.samsung.com/favicon.ico'],
            ['name' => 'Sony Corporation', 'address' => '7-1, Uchisaiwai-cho, Chiyoda-ku, Tokyo 101-8501, Japan', 'phone' => '81-3-3458-1111', 'email' => 'support@sony.com', 'website' => 'https://www.sony.com', 'logo' => 'https://www.sony.com/favicon.ico'],
            ['name' => 'Panasonic Corporation', 'address' => '1006, Oaza Kadoma, Kadoma-shi, Osaka 571-8506, Japan', 'phone' => '81-6-6951-1111', 'email' => 'support@panasonic.com', 'website' => 'https://www.panasonic.com', 'logo' => 'https://www.panasonic.com/favicon.ico'],
            ['name' => 'Canon Inc.', 'address' => '1000, Oaza Kashimada, Kameyama-shi, Mie 518-0792, Japan', 'phone' => '81-59-321-1111', 'email' => 'support@canon.com', 'website' => 'https://www.canon.com', 'logo' => 'https://www.canon.com/favicon.ico'],
            ['name' => 'Sony Group Corporation', 'address' => '7-1, Uchisaiwai-cho, Chiyoda-ku, Tokyo 101-8501, Japan', 'phone' => '81-3-3458-1111', 'email' => 'support@sony.com', 'website' => 'https://www.sony.com', 'logo' => 'https://www.sony.com/favicon.ico'],
        ];

        if ($this->faker->boolean(50)) {
            return $this->faker->randomElement($realCompanies);
        }

        return [
            'name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'website' => $this->faker->url(),
            'logo' => $this->faker->imageUrl(),
        ];
    }
}
