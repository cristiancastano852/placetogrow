<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $microsite->name }}
        </h2>
    </x-slot>

    <div class="flex w-full justify-center my-4">
        <div class="container align-middle p-4 sm:p-6 lg:p-8 bg-white">
            <div class="mb-4">
                <label class="block text-gray-700">{{ trans('sites.category') }}</label>
                <p class="text-gray-900">{{ $microsite->category->name }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">{{ trans('sites.slug') }}</label>
                <p class="text-gray-900">{{ $microsite->slug }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">{{ trans('sites.name') }}</label>
                <p class="text-gray-900">{{ $microsite->name }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">{{ trans('sites.document_type') }}</label>
                <p class="text-gray-900">{{ $microsite->document_type }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">{{ trans('sites.document_number') }}</label>
                <p class="text-gray-900">{{ $microsite->document_number }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">{{ trans('sites.logo') }}</label>
                <img src="{{ $microsite->logo }}" alt="Logo" class="w-32 h-auto">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">{{ trans('sites.currency') }}</label>
                <p class="text-gray-900">{{ $microsite->currency }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">{{ trans('sites.site_type') }}</label>
                <p class="text-gray-900">{{ $microsite->site_type }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">{{ trans('sites.payment_expiration') }}</label>
                <p class="text-gray-900">{{ $microsite->payment_expiration }} minutos</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">{{ trans('sites.created_at') }}</label>
                <p class="text-gray-900">{{ $microsite->created_at }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">{{ trans('sites.updated_at') }}</label>
                <p class="text-gray-900">{{ $microsite->updated_at }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
