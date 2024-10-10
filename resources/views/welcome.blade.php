<x-guest-layout>
    <div class="container mx-auto">
        <div class="flex justify-center">
            <div class="flex w-1/2 flex-col bg-white border shadow-sm rounded-xl p-4 md:p-5 max-w-50 mt-10">
                <h3 class="text-lg font-bold text-gray-800">
                    La vaca que más canta
                </h3>
                <p class="mt-1 mb-4 text-xs font-medium uppercase text-gray-500">
                    Realiza todas tus donaciones de forma segura.
                </p>
                <form action="{{ route('payments.store') }}" method="POST">
                    @csrf

                    <div class="space-y-3 mb-4">
                        <textarea name="description" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" rows="2">Mi donación a esta vaca para contribuir a la formación de desarrolladores PHP</textarea>
                    </div>
                    <div class="space-y-3 mb-4">
                        <input required type="number" min="1" max="999999999999" value="10000" name="amount"
                            class="py-3 px-4 block min-w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                            placeholder="Digita el valor a pagar">
                    </div>
                    <select required name="microsite_id"
                        class="mb-4 py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                        <option>Sitios disponibles</option>
                        @foreach ($sites as $microsite)
                            <option @selected($loop->first) value="{{ $microsite->id }}">{{ $microsite->name }}</option>
                        @endforeach
                    </select>
                    <select required name="currency"
                        class="mb-4 py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                        <option>Moneda</option>
                        @foreach ($currencies as $currency)
                            <option @selected($loop->first) value="{{ $currency }}">{{ $currency }}</option>
                        @endforeach
                    </select>

                    <p class="mt-1 mb-4 text-xs font-medium uppercase text-gray-500">
                        Tus datos
                    </p>

                    <div class="space-y-3 mb-4">
                        <input required type="text" value="John" name="name"
                            class="py-3 px-4 block min-w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                            placeholder="Tu nombre">
                    </div>

                    <div class="space-y-3 mb-4">
                        <input required type="text" value="Doe" name="last_name"
                            class="py-3 px-4 block min-w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                            placeholder="Tu apellido">
                    </div>

                    <div class="space-y-3 mb-4">
                        <input required type="text" value="john.doe@ghostmail.com" name="email"
                            class="py-3 px-4 block min-w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                            placeholder="Tu correo">
                    </div>

                    <div class="space-y-3 mb-4">
                        <input required type="text" value="10025553366" name="document_number"
                            class="py-3 px-4 block min-w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                            placeholder="Tu número de documento">
                    </div>

                    <select required name="document_type"
                        class="mb-4 py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                        <option>Tipo de documento</option>
                        @foreach ($documentTypes as $documentType)
                            <option @selected($loop->first) value="{{ $documentType }}">{{ $documentType }}</option>
                        @endforeach
                    </select>

                    <select required name="gateway"
                        class="mb-4 py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none">
                        <option>Pasarela de pago</option>
                        @foreach ($gateways as $gateway)
                            <option @selected($loop->first) value="{{ $gateway['value'] }}">{{ $gateway['text'] }}</option>
                        @endforeach
                    </select>

                    <button type="submit" type="button"
                        class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                        Pagar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
