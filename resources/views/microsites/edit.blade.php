<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('sites.title') }}
            </h2>
            <a href="{{ route('microsites.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                <em class="fa-solid fa-arrow-left"></em>
            </a>
        </div>
    </x-slot>
    <div class="flex w-full justify-center my-4">
        <div class="container align-middle p-4 sm:p-6 lg:p-8 bg-white">
            <form action="{{ route('microsites.update', $microsite) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700">{{ __('sites.category') }}</label>
                    <select name="category_id" id="category_id" class="w-full border-gray-300 rounded" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if ($category->id == $microsite->category_id) selected @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">{{ __('sites.name') }}</label>
                    <input type="text" name="name" id="name" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $microsite->name }}" />
                    @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">{{ __('sites.slug') }}</label>
                    <input type="text" name="slug" id="slug" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $microsite->slug }}" />
                    @error('slug')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">{{ __('sites.document_type') }}</label>
                    <input type="text" name="document_type" id="document_type" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $microsite->document_type }}" />
                    @error('document_type')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">{{ __('sites.document_number') }}</label>
                    <input type="text" name="document_number" id="document_number" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $microsite->document_number }}" />
                    @error('document_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">{{ __('sites.logo') }}</label>
                    <input type="text" name="logo" id="logo" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $microsite->logo }}" />
                    @error('logo')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">{{ __('sites.currency') }}</label>
                    <input type="text" name="currency" id="currency" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $microsite->currency }}" />
                    @error('currency')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">{{ __('sites.site_type') }}</label>
                    <input type="text" name="site_type" id="site_type" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $microsite->site_type }}" />
                    @error('site_type')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">{{ __('sites.payment_expiration') }}</label>
                    <input type="text" name="payment_expiration" id="payment_expiration" class="form-input rounded-md shadow-sm mt-1 block w-full" value="{{ $microsite->payment_expiration }}" />
                    @error('payment_expiration')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">{{ __('Guardar') }}</button>
            </form>
        </div>
    </div>
</x-app-layout>
