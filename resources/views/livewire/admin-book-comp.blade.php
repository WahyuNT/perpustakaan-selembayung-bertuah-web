<div>
    <div class="flex justify-between mb-3 mt-5">
        <div class="div items-center">
            <h1 class="text-2xl font-semibold text-gray-900">Data Buku</h1>
        </div>
        <div class="flex justify-end items-center gap-2">
            <div class="div">
                <input wire:model.live="search" type="text"
                    class="bg-white w-full  p-2 placeholder:italic  outline-slate-300 outline-1  rounded-lg focus:outline-slate-300"
                    placeholder="Masukkan Pencarian">
            </div>
            <div class="div">

                <button @click="$dispatch('open-modal')" type="button"
                    class="flex items-center justify-center cursor-pointer px-4 py-2 text-sm font-medium bg-[var(--primary)] border-2 text-white border-[var(--primary)] rounded-lg hover:brightness-95 hover:scale-105 hover:bg-[var(--primary)] hover:text-white hover:shadow-md transition duration-150 ease-in-out">
                    <i class="fa-solid fa-plus me-2"></i>
                    Tambah Buku
                </button>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
            <thead class="text-xs text-white uppercase bg-[#164f81]  ">
                <tr>
                    <th scope="col" class="px-6 py-4 text-center">
                        No
                    </th>
                    <th scope="col" class="px-6 py-4 ">
                        Cover
                    </th>
                    <th scope="col" class="px-6 py-4 ">
                        Judul
                    </th>
                    <th scope="col" class="px-6 py-4 ">
                        Deskripsi
                    </th>
                    <th scope="col" class="px-6 py-4 text-center">
                        Penulis
                    </th>
                    <th scope="col" class="px-6 py-4 text-center">
                        Publisher
                    </th>
                    <th scope="col" class="px-6 py-4 text-center">
                        Kategori
                    </th>
                    <th scope="col" class="px-6 py-4 text-center">
                        Rilis
                    </th>
                    <th scope="col" class="px-6 py-4 text-center">
                        Stok
                    </th>
                    <th scope="col" class="px-6 py-4 text-center">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-4 text-center">
                        Tipe
                    </th>
                    <th scope="col" class="px-6 py-4 text-center">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>

                @forelse ($data as  $item)
                    <tr class="odd:bg-white even:bg-gray-100 border-b border-gray-200 ">
                        <td scope="row" class="px-6 py-3 align-top text-center font-normal text-gray-900 ">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                        </td>

                        <td class="px-6 py-3 align-top    text-gray-900 font-normal ">
                            <div class="relative cursor-pointer group w-[80px] " @click="$dispatch('open-modal2')">
                                <img class="rounded-md w-full h-full object-cover" src="{{ $item->cover }}"
                                    alt="">
                                <div
                                    class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-md">

                                    <i class="fa-solid fa-magnifying-glass-plus text-white text-2xl"></i>
                                </div>
                            </div>

                        </td>
                        <td class="px-6 py-3 align-top   text-gray-900 font-normal ">
                            {{ $item->title }}
                        </td>
                        <td class="px-6 py-3 align-top   text-xs text-gray-900 font-normal ">
                            {{-- <button type="button"
                                class="cursor-pointer pt-1 hover:brightness:95 text-[var(--primary)] hover:scale-120 rounded-full">
                                <i class="fa-solid fa-eye"></i>
                            </button> --}}
                            {{ Str::limit($item->deskripsi, 150, '...') }}
                        </td>
                        <td class="px-6 py-3 align-top  text-center text-gray-900 font-normal ">
                            {{ $item->author }}
                        </td>
                        <td class="px-6 py-3 align-top  text-center text-gray-900 font-normal ">
                            {{ $item->publisher }}-
                        </td>
                        <td class="px-6 py-3 align-top  text-center text-gray-900 font-normal ">
                            {{ $item->category }}-
                        </td>
                        <td class="px-6 py-3 align-top  text-center text-gray-900 font-normal ">
                            {{ \Carbon\Carbon::parse($item->realese_date)->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-6 py-3 align-top  text-center text-gray-900 font-normal ">
                            {{ $item->stock }}
                        </td>
                        <td class="px-6 py-3 align-top  text-center text-gray-900 font-normal ">
                            @if ($item->status == 'available')
                                <div data-tooltip-target="tooltip-status-available"
                                    class="text-xs pt-1 text-green-500 "><i
                                        class="fa-solid scale-130 fa-circle-check"></i></div>

                                <div id="tooltip-status-available" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    Tersedia
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            @else
                                <div data-tooltip-target="tooltip-status-not-available"
                                    class=" text-xs pt-1 text-red-500 "><i
                                        class="fa-solid scale-130 fa-circle-xmark"></i></div>

                                <div id="tooltip-status-not-available" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
                                    Tidak Tersedia
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                            @endif
                        </td>
                        <td class="px-6 py-3 align-top  text-center text-gray-900 font-normal ">
                            {{ ucfirst($item->type) }}
                        </td>
                        <td class="px-6 flex py-3 text-center text-gray-900 font-normal gap-1 ">

                            @if ($confirmDelete != null && $confirmDelete == $item->id)
                                <div class="flex flex-col">

                                    <small class="text-[13px] flex">Apa anda yakin?</small>
                                    <div class="flex gap-1">
                                        <button wire:click="$set('confirmDelete', null)"
                                            class=" px-2 text-[10px] text-white  cursor-pointer bg-blue-500 hover:text-white-500 hover:bg-blue-600 rounded-full p-1">
                                            Batal
                                        </button>
                                        <button wire:click="delete({{ $item->id }})"
                                            class=" px-2 text-[10px] text-white cursor-pointer bg-red-500 hover:text-white-500 hover:bg-red-600 rounded-full p-1">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col gap-1">
                                    <button wire:click="edit({{ $item->id }})" @click="$dispatch('open-modal')"
                                        type="button"
                                        class=" border-1 bg-blue-500  text-white cursor-pointer rounded-md p-1.5  hover:brightness-95 hover:scale-120    transition duration-100 ease-in-out">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                    <button wire:click="$set('confirmDelete', {{ $item->id }})" type="button"
                                        class=" border-1   bg-red-500 text-white  cursor-pointer rounded-md p-1.5  hover:brightness-95 hover:scale-120  transition duration-100 ease-in-out">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center py-4 text-gray-900">Data Tidak Ditemukan</td>
                    </tr>
                @endforelse



            </tbody>
        </table>
    </div>

    <div class="mt-3 ">
        {{ $data->links('vendor.livewire.tailwind') }}
    </div>


    <div x-data="{ open: false }" x-on:open-modal.window="open = true" x-on:close-modal.window="open = false"
        x-show="open" class="relative z-50 " x-cloak>

        <div @click.self="open = false" x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-50"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-50"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black opacity-50">
        </div>

        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden ">
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                class="relative p-4 w-full max-w-4xl max-h-full mx-auto">
                <div class="relative bg-white rounded-xl shadow-sm">

                    <div
                        class="flex items-center justify-between px-4 py-2 border-b rounded-t-xl bg-primary border-gray-200">
                        <h3 class="text-lg font-semibold text-white">
                            Tambah Buku
                        </h3>
                        <button wire:click="resetInput" type="button" @click="open = false"
                            class="text-white flex cursor-pointer bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-xl text-sm w-8 h-8 ms-auto justify-center items-center active:scale-110 transition duration-150 ease-in-out">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4 " wire:loading.class="relative flex justify-center items-center">

                        <span wire:loading class="loader scale-50  my-5"></span>

                        <div class="block" wire:loading.class="hidden">
                            <div class="flex flex-wrap">
                                <div class="w-1/3">
                                    <img class=" rounded-xl" src="{{ $item->cover }}" alt="">
                                </div>
                                <div class="w-2/3 flex flex-col justify-between">
                                    <div class="div">

                                        <div class="flex ">
                                            <div class="w-1/2 ps-3">
                                                <div class=" w-full mt-1   items-start">
                                                    <x-input symbol="*" typeWire="defer" inputId="title"
                                                        label="Judul" type="text" wireModel="title"
                                                        placeholder="Masukkan Judul" />
                                                </div>
                                                <div class="w-full flex">
                                                    <div class=" w-1/2 mt-1 pe-2  items-start">
                                                        <x-input symbol="*" typeWire="defer" inputId="stock"
                                                            label="Stok" type="text" wireModel="stock"
                                                            placeholder="Masukkan Stok" />
                                                    </div>
                                                    <div class=" w-1/2 mt-1   items-start">
                                                        <x-select symbol="*" selectId="type" label="Tipe"
                                                            wireModel="type" placeholder="Tipe" :options="[
                                                                'literasi' => 'Literasi',
                                                                'paketan' => 'Paketan',
                                                            ]" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-1/2 ps-2 items-end flex  flex-col justify-between">
                                                <div class=" w-full mt-1   items-start">
                                                    <x-input symbol="*" typeWire="defer" inputId="publisher"
                                                        label="Penerbit" type="text" wireModel="publisher"
                                                        placeholder="Masukkan Penerbit" />
                                                </div>
                                                <div class="w-full flex">

                                                    <div class=" w-1/2 mt-1   items-start">
                                                        <x-input symbol="‎" typeWire="defer"
                                                            inputId="realese_date" label="Tanggal Rilis"
                                                            type="date" wireModel="realese_date"
                                                            placeholder="Masukkan Tanggal Rilis" />
                                                    </div>
                                                    <div class=" w-1/2 mt-1  ps-2 items-start">
                                                        <x-select symbol="*" selectId="status" label="Status"
                                                            wireModel="status" placeholder="Status"
                                                            :options="[
                                                                'available' => 'Tersedia',
                                                                'borrowed' => 'Tidak Tersedia',
                                                            ]" />
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <div class=" w-full mt-1 ps-3 items-start">
                                            <label class="text-sm text-gray-500">Deskripsi<span
                                                    class="text-red-500 text-lg">‎</span></label>
                                            <textarea wire:model.defer="deskripsi" class="w-full rounded-lg focus:outline-gray-300  bg-gray-200 p-2 text-sm"
                                                name="" id="" cols="30" rows="5"></textarea>
                                            @error($deskripsi)
                                                <div class="text-red-500 text-sm">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="w-full  items-end justify-end mt-auto flex ">
                                        @if ($editId == null)
                                            <button type="button" wire:click="store"
                                                class="flex items-center  justify-center cursor-pointer px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:brightness-95 hover:scale-105 hover:shadow-md transition duration-150 ease-in-out">
                                                Tambah Buku
                                            </button>
                                        @else
                                            <button type="button" wire:click="storeEdit"
                                                class="flex items-center  justify-center cursor-pointer px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:brightness-95 hover:scale-105 hover:shadow-md transition duration-150 ease-in-out">
                                                Simpan Perubahan
                                            </button>
                                        @endif
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div x-data="{ openSecond: false }" x-cloak x-show="openSecond" x-on:open-modal2.window="openSecond = true"
        x-on:close-modal2.window="openSecond = false"
        class="fixed inset-0 z-20 flex items-center justify-center bg-black/50">
        <div class="  justify-center flex p-5 shadow-lg  " @click.away="openSecond = false">

            <img class="h-dvh rounded-lg " src="{{ asset('images/books/animal_farm_new.jpg') }}" alt="">
        </div>
    </div>

</div>
