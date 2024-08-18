<x-filament-panels::page>
    <div class="grid grid-cols-2 gap-4 place-content-center">
        <a href="#" class="flex items-center justify-center rounded h-32 cursor-pointer text-danger-600 bg-primary-500"><span class="text-2xl">{{ __('filament-panels::pages/dashboard.title') }}</span></a>
        <a href="{{ route('filament.company.resources.locations.index', Filament\Facades\Filament::getTenant()) }}" class="flex items-center justify-center rounded h-32 cursor-pointer text-danger-600 bg-gray-300"><span class="text-2xl">{{ __('module_names.locations.plural_label') }}</span></a>
        @if (auth()->user()->hasRole('company_admin'))
            <a href="{{ route('filament.company.resources.users.index', Filament\Facades\Filament::getTenant()) }}" class="flex items-center justify-center rounded row-start-2 h-32 cursor-pointer text-danger-600 bg-gray-100"><span class="text-2xl">{{ __('module_names.users.plural_label') }}</span></a>
        @endif
        <a href="#" class="flex items-center justify-center rounded row-start-2 h-32 cursor-pointer text-danger-600 bg-primary-600"><span class="text-2xl">...</span></a>
    </div>
    {{-- <div class="grid grid-cols-2 gap-4 place-content-center">
        <div class="flex items-center justify-center rounded h-32 cursor-pointer text-danger-600 bg-primary-500">{{ __('filament-panels::pages/dashboard.title') }}</div>
        <div class="flex items-center justify-center rounded h-32 cursor-pointer text-danger-600 bg-gray-300">{{ __('module_names.locations.plural_label') }}</div>
        <div class="flex items-center justify-center rounded row-start-2 h-32 cursor-pointer text-danger-600 bg-gray-100">{{ __('module_names.users.plural_label') }}</div>
        <div class="flex items-center justify-center rounded row-start-2 h-32 cursor-pointer text-danger-600 bg-primary-600">...</div>
    </div> --}}
    {{-- <div class="grid grid-cols-2 gap-4 place-content-center">
        <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center justify-center rounded h-32 cursor-pointer text-danger-600 bg-primary-500"><span>{{ __('filament-panels::pages/dashboard.title') }}</span></a>
        <a href="{{ route('filament.company.resources.locations.index') }}" class="flex items-center justify-center rounded h-32 cursor-pointer text-danger-600 bg-gray-300"><span>{{ __('module_names.locations.plural_label') }}</span></a>
        <a href="{{ route('filament.company.resources.users.index') }}" class="flex items-center justify-center rounded row-start-2 h-32 cursor-pointer text-danger-600 bg-gray-100"><span>{{ __('module_names.users.plural_label') }}</span></a>
        <a href="#" class="flex items-center justify-center rounded row-start-2 h-32 cursor-pointer text-danger-600 bg-primary-600"><span>...</span></a>
    </div> --}}
    {{-- <div class="bg-gray-100 p-4 sm:p-8 md:p-16 mt-20">
        <div class="container mx-auto">
          <div class="grid grid-cols-2 sm:grid-cols-2 gap-4">

            <a href="/frontend-performance"
              class="relative flex h-full flex-col rounded-md border border-gray-200 bg-white p-2.5 hover:border-gray-400 sm:rounded-lg sm:p-5">
              <span class="text-md mb-0 font-semibold text-gray-900 hover:text-black sm:mb-1.5 sm:text-xl">
                Frontend Performance
              </span>
              <span class="text-sm leading-normal text-gray-400 sm:block">
                Detailed list of best practices to improve your frontend performance
              </span>
            </a>
            <a href="/api-security"
              class="relative flex h-full flex-col rounded-md border border-gray-200 bg-white p-2.5 hover:border-gray-400 sm:rounded-lg sm:p-5">
              <span class="text-md mb-0 font-semibold text-gray-900 hover:text-black sm:mb-1.5 sm:text-xl">
                API Security
              </span>
              <span class="text-sm leading-normal text-gray-400 sm:block">
                Detailed list of best practices to make your APIs secure
              </span>
            </a>
            <a href="/code-review"
              class="relative flex h-full flex-col rounded-md border border-gray-200 bg-white p-2.5 hover:border-gray-400 sm:rounded-lg sm:p-5">
              <span class="text-md mb-0 font-semibold text-gray-900 hover:text-black sm:mb-1.5 sm:text-xl">
                Code Reviews
              </span>
              <span class="text-sm leading-normal text-gray-400 sm:block">
                Detailed list of best practices for effective code reviews and quality
              </span>
            </a>
            <a href="/aws"
              class="relative flex h-full flex-col rounded-md border border-gray-200 bg-white p-2.5 hover:border-gray-400 sm:rounded-lg sm:p-5">
              <span class="text-md mb-0 font-semibold text-gray-900 hover:text-black sm:mb-1.5 sm:text-xl">
                AWS
              </span>
              <span class="text-sm leading-normal text-gray-400 sm:block">
                Detailed list of best practices for Amazon Web Services (AWS)
              </span>
            </a>
          </div>
        </div>
      </div> --}}
</x-filament-panels::page>
