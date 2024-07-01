<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between w-full">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear sitio de pago
            </h2>

            <a href="{{ route('microsites.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                <em class="fa-solid fa-arrow-left"></em>
            </a>
        </div>
    </x-slot>
    <div class="flex w-full justify-center my-4">
        <div class="container align-middle p-4 sm:p-6 lg:p-8 bg-white">
            <form action="{{ route('microsites.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Nombre</label>
                    <input type="text" name="name" id="name" class="w-full border-gray-300 rounded" required>
                    @error('name')
                        <div class="alert alert-danger text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="slug" class="block text-gray-700">Slug</label>
                    <input type="text" name="slug" id="slug" class="w-full border-gray-300 rounded" required>
                    @error('slug')
                        <div class="alert alert-danger text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700">Categorías</label>
                    <select name="category_id" id="category_id" class="w-full border-gray-300 rounded" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="alert alert-danger text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="document_type" class="block text-gray-700">Tipo de documento</label>
                    <select name="document_type" id="document_type" class="w-full border-gray-300 rounded" required>
                        @foreach ($documentTypes as $documentType)
                            <option value="{{ $documentType->name }}">{{ $documentType->name }}</option>
                        @endforeach
                    </select>
                    @error('document_type')
                        <div class="alert alert-danger text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="document_number" class="block text-gray-700">Número de documento</label>
                    <input type="text" name="document_number" id="document_number"
                        class="w-full border-gray-300 rounded" required>
                    @error('document_number')
                        <div class="alert alert-danger text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="logo" class="block text-gray-700">Logo</label>
                    <input type="text" name="logo" id="logo" class="w-full border-gray-300 rounded" required>
                    @error('logo')
                        <div class="alert alert-danger text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="currency" class="block text-gray-700">Moneda</label>
                    <select name="currency" id="currency" class="w-full border-gray-300 rounded" required>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->name }}">{{ $currency->name }}</option>
                        @endforeach
                    </select>
                    @error('currency')
                        <div class="alert alert-danger text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="site_type" class="block text-gray-700">Tipo de sitio</label>
                    <select name="site_type" id="site_type" class="w-full border-gray-300 rounded" required>
                        @foreach ($micrositesTypes as $siteType)
                            <option value="{{ $siteType->name }}">{{ $siteType->name }}</option>
                        @endforeach
                    </select>
                    @error('site_type')
                        <div class="alert alert-danger text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="payment_expiration" class="block text-gray-700">Expiración del pago (Minutos)</label>
                    <input type="number" name="payment_expiration" id="payment_expiration"
                        class="w-full border-gray-300 rounded" required>
                    @error('payment_expiration')
                        <div class="alert alert-danger text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
            </form>
        </div>
    </div>
</x-app-layout>
