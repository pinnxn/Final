  <!DOCTYPE html>
  <html lang="en">

  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Tailwind Starter Template - Nordic Shop: Tailwind Toolbox</title>
      <meta name="description" content="Free open source Tailwind CSS Store template">
      <meta name="keywords" content="tailwind,tailwindcss,tailwind css,css,starter template,free template,store template, shop layout, minimal, monochrome, minimalistic, theme, nordic">

      <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css" />

      <link href="https://fonts.googleapis.com/css?family=Work+Sans:200,400&display=swap" rel="stylesheet">

      <style>
          .work-sans {
              font-family: 'Work Sans', sans-serif;
          }

          #menu-toggle:checked+#menu {
              display: block;
          }

          .hover\:grow {
              transition: all 0.3s;
              transform: scale(1);
          }

          .hover\:grow:hover {
              transform: scale(1.02);
          }

          .carousel-open:checked+.carousel-item {
              position: static;
              opacity: 100;
          }

          .carousel-item {
              -webkit-transition: opacity 0.6s ease-out;
              transition: opacity 0.6s ease-out;
          }

          #carousel-1:checked~.control-1,
          #carousel-2:checked~.control-2,
          #carousel-3:checked~.control-3 {
              display: block;
          }

          .carousel-indicators {
              list-style: none;
              margin: 0;
              padding: 0;
              position: absolute;
              bottom: 2%;
              left: 0;
              right: 0;
              text-align: center;
              z-index: 10;
          }

          #carousel-1:checked~.control-1~.carousel-indicators li:nth-child(1) .carousel-bullet,
          #carousel-2:checked~.control-2~.carousel-indicators li:nth-child(2) .carousel-bullet,
          #carousel-3:checked~.control-3~.carousel-indicators li:nth-child(3) .carousel-bullet {
              color: #000;
              /*Set to match the Tailwind colour you want the active one to be */
          }
      </style>

  </head>

  <body class="bg-white text-gray-600 work-sans leading-normal text-base tracking-normal">

      <section class="bg-white py-8">

          <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">

              <nav id="company" class="w-full z-30 top-0 px-6 py-1">
                  <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-3">

                      <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-xl " href="#">
                          Company
                      </a>
                  </div>
              </nav>
              <!-- img1 -->
              @foreach ($companies as $company)
              <div class="w-full md:w-1/3 xl:w-1/4 p-6 flex flex-col">
                  <a href="{{ $company->website }}" target="_blank">
                      @if($company->logo && filter_var($company->logo, FILTER_VALIDATE_URL))
                      <!-- ถ้า logo เป็น URL ที่ถูกต้อง -->
                      <img class="hover:grow hover:shadow-lg" src="{{ $company->logo }}" alt="Company Logo" style="max-height: 100px; object-fit: contain;">
                      @elseif($company->logo)
                      <!-- ถ้า logo เป็นชื่อไฟล์ แต่ไม่ใช่ URL ให้แสดงจาก storage -->
                      <img class="hover:grow hover:shadow-lg" src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo">
                      @else
                      <!-- ถ้าไม่มี logo ให้แสดงข้อความ -->
                      <p>No logo available</p>
                      @endif
                      <div class="pt-3 flex items-center justify-between">
                          <p class="text-lg font-bold">{{ $company->name }}</p>
                      </div>

                      <p class="pt-1 text-gray-900 text-sm">Email: {{ $company->email }}</p>
                  </a>
              </div>
              @endforeach
          </div>

      <footer class="container mx-auto bg-white py-8 border-t border-blue-400">
      </footer>

  </body>

  </html>