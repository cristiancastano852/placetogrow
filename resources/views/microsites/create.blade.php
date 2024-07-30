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
                <div class="mb-4">
                    <label for="custom_fields" class="block text-gray-700">Campos personalizados</label>
                    <div id="custom-fields-container" class="space-y-2">
                    </div>
                    <button type="button" id="add-custom-field" class="bg-green-500 text-white px-4 py-2 rounded mt-2">Añadir campo</button>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('add-custom-field').addEventListener('click', function() {
            const container = document.getElementById('custom-fields-container');
            const index = container.children.length;

            const fieldHtml = `
                <div class="custom-field mb-2 border p-2 rounded">
                    <label class="block text-gray-700">Etiqueta</label>
                    <input type="text" name="payment_fields[${index}][label]" class="w-full border-gray-300 rounded" required>
                    
                    <label class="block text-gray-700">Tipo</label>
                    <select name="payment_fields[${index}][type]" class="w-full border-gray-300 rounded" required>
                        <option value="text">Texto</option>
                        <option value="number">Número</option>
                        <option value="email">Correo</option>
                        <!-- Otros tipos de campos -->
                    </select>
                    
                    <label class="block text-gray-700">Longitud máxima</label>
                    <input type="number" name="payment_fields[${index}][max_length]" class="w-full border-gray-300 rounded" required>
                    
                    <label class="block text-gray-700">Opcional</label>
                    <input type="checkbox" name="payment_fields[${index}][optional]" class="rounded">
                    
                    <button type="button" class="remove-custom-field bg-red-500 text-white px-4 py-2 rounded mt-2">Eliminar campo</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', fieldHtml);
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-custom-field')) {
                e.target.closest('.custom-field').remove();
            }
        });
    </script>
</x-app-layout>
