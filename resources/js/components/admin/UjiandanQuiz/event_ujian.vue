<template>
    <div>
        <section class="py-2 px-4">
            <div class="max-w-8xl mx-auto">
                <div class="w-full bg-white rounded shadow p-4">
                    <div class="flex flex-wrap w-full justify-between items-start gap-3 mb-3">
                        <div class="flex flex-wrap w-full sm:w-1/2 items-start gap-3 mb-3">
                            <div class="w-full sm:w-1/4">
                                <select class="w-full border rounded px-3 py-2" v-model="pagination.per_page"
                                    @change="viewItem()">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                </select>
                            </div>

                            <div class="w-full sm:w-2/4">
                                <input type="search" class="w-full border rounded px-3 py-2" v-model="search"
                                    placeholder="Cari..">
                            </div>
                        </div>
                        <div class="w-auto">
                            <button @click="openModal" class="btn-primary text-white text-sm px-3 py-2 rounded">
                                Tambah Jadwal
                            </button>
                        </div>
                    </div>
                    <SkeletonTable v-if="loadingView" :rows="5" :columns="5" />

                    <SortableTableTail v-else :data="items" :columns="columns" :hasActions="true"
                        style="min-height: 300px;">
                        <template #actions="{ row }">
                            <div class="relative inline-block text-left group">
                                <button
                                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-md">
                                    Action
                                    <svg class="w-5 h-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12l-4-4h8l-4 4z" />
                                    </svg>
                                </button>

                                <div
                                    class="invisible group-focus-within:visible opacity-0 group-focus-within:opacity-100 transition-all duration-300 absolute right-0 w-48 mt-2 bg-white border rounded-md shadow-xl z-50">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100"
                                        @click="toImportPeserta(row)">Import Peserta</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100"
                                        @click="toGenerateSoalMasal(row)">Generate Soal</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100"
                                        @click="toExportSoalMasal(row)">Export Hasil Ujian</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100"
                                        @click="toEdit(row)">Edit</a>
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-500 hover:text-white"
                                        @click="deleteItem(row.id)">Delete</a>
                                </div>
                            </div>

                        </template>
                        <template #custom="{ row, column }">
                            <span v-if="column.key == 'file_event'">
                                <ul>
                                    <li class="url" v-for="fls in row.file_event"
                                        @click="get_cloud_url(row.id, fls.path)">
                                        {{ fls.name }}
                                    </li>
                                </ul>
                            </span>

                            <span v-if="column.key == 'jumlah_peserta'">
                                <label class="url" @click="openModalPesertaList(row)">
                                    {{ row.jumlah_peserta }} Orang
                                </label>
                            </span>



                            <span v-if="column.key == 'created_at'">
                                <TanggalIndo :longdate="false" :tanggal="row.created_at" :time="true"></TanggalIndo>
                            </span>
                        </template>
                    </SortableTableTail>

                    <PaginationTail :pagination="pagination" @page-changed="viewItem" />
                </div>

                <!-- Modal -->
                <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center" v-if="showModal"
                    ref="modal">
                    <div class="bg-white rounded-lg w-7/10 md:w-2/3 max-w-4xl overflow-auto max-h-[90vh]">

                        <form @submit.prevent="createItem()">
                            <div class="border-b px-6 py-4 flex justify-between items-center">
                                <h5 class="text-lg font-semibold">Jadwal</h5>
                                <button type="button" class="text-gray-500 hover:text-gray-700"
                                    aria-label="Close">✖</button>
                            </div>
                            <div ref="notaContent" class="px-6 py-4 bg-white text-black">
                                <div class="flex flex-wrap justify-between gap-4 mb-6">
                                    <div class="w-full">
                                        <div class="w-full">
                                            <label class="block text-sm font-medium mb-1">Nama Jadwal</label>
                                            <div class="w-full">
                                                <input type="text" v-model="form.nama_event"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="Nama Event..." />
                                            </div>
                                        </div>
                                        <div class="w-full">
                                            <label class="block text-sm font-medium mb-1">Tanggal</label>
                                            <div class="w-full">
                                                <input type="date" v-model="form.tanggal_event"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="Judul..." />
                                            </div>
                                        </div>
                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-1">Deskripsi</label>
                                            <div class="w-full">
                                                <textarea type="text" rows="4" v-model="form.deskripsi"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="Deskripsi..."></textarea>
                                            </div>
                                        </div>



                                        <div class="w-full mt-3">
                                            <div class="w-full">
                                                <div class="w-full mt-3">
                                                    <label class="block text-sm font-medium mb-1">Files</label>
                                                    <div @dragover.prevent="isDragging = true"
                                                        @dragleave.prevent="isDragging = false"
                                                        @drop.prevent="handleDrop"
                                                        :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                                        class="w-full border-2 border-dashed border-gray-300 rounded-md px-6 py-8 text-center transition-colors cursor-pointer hover:border-gray-400"
                                                        @click="$refs.fileInput.click()">
                                                        <svg class="mx-auto h-12 w-12 text-gray-400"
                                                            stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                            <path
                                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <p class="mt-2 text-sm text-gray-600">
                                                            <span class="font-semibold">Click to upload</span> or drag
                                                            and drop
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1">Multiple files allowed</p>

                                                        <input ref="fileInput" type="file" multiple
                                                            @change="handleFileSelect" class="hidden" />
                                                    </div>

                                                    <!-- Preview file yang dipilih -->
                                                    <div v-if="form.files" class="mt-3 space-y-2">
                                                        <div v-for="(file, index) in form.files" :key="index"
                                                            class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded border">
                                                            <span class="text-sm text-gray-700 truncate">{{ file.name
                                                                }}</span>
                                                            <button @click="removeFile(index)"
                                                                class="text-red-500 hover:text-red-700 ml-2">
                                                                <svg class="w-5 h-5" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="border-t px-6 py-4 flex justify-end gap-2">
                                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded"
                                    @click="closeModal()">Tutup</button>
                                <button type="button" @click="createItem"
                                    class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>



                <!-- Modal Peserta Event -->
                <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center"
                    v-if="showModalPesertaEvent" ref="modal">
                    <div class="bg-white rounded-lg w-7/10 md:w-2/3 max-w-4xl overflow-auto max-h-[90vh]">

                        <form @submit.prevent="createItem()">
                            <div class="border-b px-6 py-4 flex justify-between items-center">
                                <h5 class="text-lg font-semibold">Event</h5>
                                <button type="button" @click="closeModalPeserta()"
                                    class="text-gray-500 hover:text-gray-700" aria-label="Close">✖</button>
                            </div>
                            <div ref="notaContent" class="px-6 py-4 bg-white text-black">
                                <div class="flex flex-wrap justify-between gap-4 mb-6">
                                    <div class="w-full">
                                        <div class="w-full">
                                            <label class="block text-sm font-bold mb-1">Nama Event</label>
                                            <div class="w-full">
                                                <div
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                    {{ form.nama_event }}</div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="w-full mt-3">
                                        <label class="block text-sm font-bold mb-1">Model Input Data</label>
                                        <div class="flex items-center gap-6">
                                            <label class="flex items-center space-x-2 cursor-pointer group">
                                                <input type="radio" v-model="pesertaEvent.modeImport" value="Manual"
                                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                <span
                                                    class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors">
                                                    Input Manual
                                                </span>
                                            </label>

                                            <label class="flex items-center space-x-2 cursor-pointer group">
                                                <input type="radio" v-model="pesertaEvent.modeImport" value="Import"
                                                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                <span
                                                    class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors">
                                                    Import File
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="w-full" v-if="pesertaEvent.modeImport == 'Manual'">


                                        <div class="w-full mt-3">
                                            <div class="w-full">
                                                <label class="block text-sm font-bold mb-1">Modul</label>
                                                <div class="w-full">
                                                    <select
                                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                        v-model="pesertaEvent.modul">
                                                        <option disabled value="">-- Pilih Modul --</option>
                                                        <option v-for="mod in optionModule" :key="mod.id"
                                                            :value="mod.id">{{
                                                                mod.nama_modul }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="w-full mt-2 mt-4 rounded-md p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-r-lg shadow-sm"
                                                    v-if="selectedModul">

                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td class="pr-4">Jumlah Soal</td>
                                                                <td>: {{ selectedModul?.jumlah_soal }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="pr-4">Lama Pengerjaan</td>
                                                                <td>: {{ selectedModul?.lama_pengerjaan }} Menit</td>
                                                            </tr>

                                                            <tr>
                                                                <td class="pr-4">Deskripsi</td>
                                                                <td>: {{ selectedModul?.deskripsi }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-bold mb-1">Waktu Mulai Ujian</label>
                                            <div class="w-full">
                                                <input type="datetime-local" v-model="pesertaEvent.waktu_mulai_ujian"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="Nama Event..." />
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-bold mb-1">Waktu Berakhir Ujian</label>
                                            <input type="datetime-local" :value="waktuBerakhirComputed" readonly
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-50" />
                                        </div>


                                        <div class="w-full mt-4">
                                            <label class="block text-sm font-bold mb-1">Jenis Peserta</label>
                                            <div class="flex items-center gap-6">
                                                <label class="flex items-center space-x-2 cursor-pointer group">
                                                    <input type="radio" v-model="pesertaEvent.per" value="Pegawai"
                                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                    <span
                                                        class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors">
                                                        Pegawai
                                                    </span>
                                                </label>

                                                <label class="flex items-center space-x-2 cursor-pointer group">
                                                    <input type="radio" v-model="pesertaEvent.per" value="Kantor"
                                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                    <span
                                                        class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors">
                                                        Kantor
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-bold mb-1">{{ pesertaEvent.per }}</label>
                                            <div class="w-full">
                                                <v-select multiple :options="itemsPeserta" :loading="isLoadingKantor"
                                                    label="nama" class="w-full" v-model="pesertaEvent.peserta"
                                                    placeholder="Pencarian.." :reduce="item => item.id"
                                                    @search="(search, loading) => onSearchKantor(search, loading, 'kantor')">
                                                    <template #no-options="{ search, searching, loading }">
                                                        <div v-if="loading">
                                                            Memuat hasil...
                                                        </div>
                                                        <div v-else-if="searching">
                                                            Tidak ada hasil ditemukan untuk "{{ search }}".
                                                        </div>
                                                        <div v-else>
                                                            Mulai mengetik untuk mencari data.
                                                        </div>
                                                    </template>
                                                </v-select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="w-full" v-else>

                                        <div class="w-full">
                                            <label class="block text-sm font-bold mb-1">Modul</label>
                                            <div class="w-full">
                                                <select
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    v-model="pesertaEvent.modul">
                                                    <option disabled value="">-- Pilih Modul --</option>
                                                    <option v-for="mod in optionModule" :key="mod.id" :value="mod.id">{{
                                                        mod.nama_modul }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label class="block text-sm font-medium mb-1">File ({{ fileAcceptImport
                                                }})</label>
                                            <input type="file" :accept="fileAcceptImport"
                                                @change="handleFileSoalChange($event)"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        </div>
                                        <div class="mt-3">
                                            <span class="url" @click="generateTemplate(pesertaEvent)">Download
                                                Template</span>
                                        </div>


                                        <div class="flex mt-3 rounded-md items-center p-4 text-black border-t-4 border-blue-300 bg-blue-50"
                                            role="alert">
                                            <div class="ms-3 font-medium">
                                                <strong>Template Kolom Excel:</strong>
                                                <ul class="mt-3">
                                                    <li><strong>Kolom A:</strong> NPP</li>
                                                    <li><strong>Kolom B:</strong> Jadwal Mulai (Cth: 2025-12-30
                                                        16:00)
                                                    </li>
                                                    <li><strong>Kolom C:</strong> Jadwal Berakhir (Cth: 2025-12-30
                                                        18:00)</li>
                                                    <li><strong>Kolom D:</strong> Modul (Ambil ID Import dari
                                                        halaman
                                                        modul)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="border-t px-6 py-4 flex justify-end gap-2">
                                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded"
                                    @click="closeModalPeserta()">Tutup</button>
                                <button type="button" @click="setPesertaEvent()"
                                    class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- modal list peserta -->

                <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center"
                    v-if="showModalPesertaList" ref="modal">
                    <div class="bg-white rounded-lg w-7/10 md:w-2/3 max-w-8xl max-h-[90vh] flex flex-col">

                        <!-- Header - Fixed (tidak scroll) -->
                        <div class="border-b px-6 py-4 flex justify-between items-center flex-shrink-0">
                            <h5 class="text-lg font-semibold">Peserta Event</h5>
                            <button type="button" @click="closeModalPesertaList()"
                                class="text-gray-500 hover:text-gray-700" aria-label="Close">✖</button>
                        </div>

                        <!-- Content - Scrollable -->
                        <div ref="notaContent" class="px-6 py-4 bg-white text-black overflow-y-auto flex-1">
                            <div class="flex flex-wrap justify-between gap-4 mb-6">
                                <div class="w-full" v-if="loadingPesertLiat">
                                    <Loading />
                                </div>

                                <div class="w-full" v-else>
                                    <div class="flex flex-wrap w-full sm:w-1/2 items-start gap-3 mb-3">
                                        <div class="w-full sm:w-1/4">
                                            <select class="w-full border rounded px-3 py-2"
                                                v-model="paginationPesertaList.per_page"
                                                @change="viewPesertaList(1, pesertaEvent.idevent)">
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="30">30</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="200">200</option>
                                            </select>
                                        </div>

                                        <div class="w-full sm:w-2/4">
                                            <input type="search" class="w-full border rounded px-3 py-2"
                                                v-model="searchPeserta" placeholder="Cari..">
                                        </div>
                                    </div>

                                    <table class="min-w-full border border-gray-300">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="px-4 py-2 border-b border-gray-300 text-left">Nama</th>
                                                <th class="px-4 py-2 border-b border-gray-300 text-left">Email</th>
                                                <th class="px-4 py-2 border-b border-gray-300 text-left">Kantor</th>
                                                <th class="px-4 py-2 border-b border-gray-300 text-left">Jadwal Mulai
                                                </th>
                                                <th class="px-4 py-2 border-b border-gray-300 text-left">Jadwal Berakhir
                                                </th>
                                                <th class="px-4 py-2 border-b border-gray-300 text-left">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="peserta in pesertaList" :key="peserta.id">
                                                <td class="px-4 py-2 border-b border-gray-300">{{ peserta?.peserta?.name
                                                }}</td>
                                                <td class="px-4 py-2 border-b border-gray-300">{{
                                                    peserta?.peserta?.email }}</td>
                                                <td class="px-4 py-2 border-b border-gray-300">

                                                    {{ peserta?.kantor?.nama
                                                    }}</td>
                                                <td class="px-4 py-2 border-b border-gray-300">
                                                    <TanggalIndo :longdate="true" :tanggal="peserta?.jadwal_mulai"
                                                        :time="true">
                                                    </TanggalIndo>
                                                </td>
                                                <td class="px-4 py-2 border-b border-gray-300">
                                                    <TanggalIndo :longdate="true" :tanggal="peserta?.jadwal_berakhir"
                                                        :time="true">
                                                    </TanggalIndo>
                                                </td>
                                                <td class="px-4 py-2 border-b border-gray-300">
                                                    <div class="relative inline-block text-left group">
                                                        <button
                                                            class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-md">
                                                            Action
                                                            <svg class="w-5 h-5 ml-2" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path d="M10 12l-4-4h8l-4 4z" />
                                                            </svg>
                                                        </button>

                                                        <div
                                                            class="invisible group-focus-within:visible opacity-0 group-focus-within:opacity-100 transition-all duration-300 absolute right-0 w-48 mt-2 bg-white border rounded-md shadow-xl z-50">

                                                            <a href="#"
                                                                @click="deletePeserta(peserta.id, peserta.event_id)"
                                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100">Hapus
                                                                Peserta</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <PaginationTail :pagination="paginationPesertaList"
                                        @page-changed="viewPesertaList" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center"
                    v-if="showModalGenerateSoal" ref="modal">
                    <div class="bg-white rounded-lg w-6/10 md:w-1/3 max-w-8xl overflow-auto max-h-[90vh]">

                        <div class="border-b px-6 py-4 flex justify-between items-center">
                            <h5 class="text-lg font-semibold">Generate Soal {{ form.nama_event }}</h5>
                            <button type="button" @click="closeModal()" class="text-gray-500 hover:text-gray-700"
                                aria-label="Close">✖</button>
                        </div>
                        <div ref="notaContent" class="px-6 py-4 bg-white text-black">
                            <div class="flex flex-wrap justify-between gap-4 mb-6">
                                <div class="w-full" v-if="loadingPesertLiat">
                                    <Loading />
                                </div>

                                <div class="w-full" v-else>

                                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative"
                                        role="alert">
                                        <span class="block sm:inline">Generate soal masal sesuai modul yang dipilih.
                                            Soal akan terbentuk
                                            tanpa menunggu peserta
                                            memasuki sesi ujian.</span>
                                    </div>
                                    <div class="w-full mt-3">
                                        <button type="button" @click="generateSoalMasal()"
                                            class="bg-blue-600 text-white px-4 py-2 rounded">Generate
                                            Soal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center"
                    v-if="showModalExportSoal" ref="modal">
                    <div class="bg-white rounded-lg w-6/10 md:w-1/3 max-w-8xl overflow-auto max-h-[90vh]">

                        <div class="border-b px-6 py-4 flex justify-between items-center">
                            <h5 class="text-lg font-semibold">Export Soal {{ form.nama_event }}</h5>
                            <button type="button" @click="closeModal()" class="text-gray-500 hover:text-gray-700"
                                aria-label="Close">✖</button>
                        </div>
                        <div ref="notaContent" class="px-6 py-4 bg-white text-black">
                            <div class="flex flex-wrap justify-between gap-4 mb-6">
                                <div class="w-full" v-if="loadingPesertLiat">
                                    <Loading />
                                </div>

                                <div class="w-full" v-else>
                                    <!-- Info -->
                                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative"
                                        role="alert">
                                        <span class="block sm:inline">
                                            Setelah export selesai, hasil akan ditampilkan pada
                                            halaman export.
                                        </span>
                                    </div>

                                    <!-- Pilihan export -->
                                    <div class="w-full mt-4">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Pilih format export
                                        </label>

                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <!-- Excel -->
                                            <label
                                                class="flex items-center gap-3 p-3 rounded border cursor-pointer hover:bg-gray-50"
                                                :class="formExport.exportFormat === 'excel' ? 'border-blue-500 ring-1 ring-blue-300 bg-blue-50' : 'border-gray-300'">
                                                <input type="radio" class="h-4 w-4" name="exportFormat" value="excel"
                                                    v-model="formExport.exportFormat" />
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-800">Excel (.xlsx)</div>
                                                    <div class="text-xs text-gray-500">Cocok untuk rekap & olah data.
                                                    </div>
                                                </div>
                                            </label>

                                            <!-- PDF -->
                                            <label
                                                class="flex items-center gap-3 p-3 rounded border cursor-pointer hover:bg-gray-50"
                                                :class="formExport.exportFormat === 'pdf' ? 'border-blue-500 ring-1 ring-blue-300 bg-blue-50' : 'border-gray-300'">
                                                <input type="radio" class="h-4 w-4" name="exportFormat" value="pdf"
                                                    v-model="formExport.exportFormat" />
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-800">PDF (.pdf)</div>
                                                    <div class="text-xs text-gray-500">Cocok untuk cetak & arsip.</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Tombol export -->
                                    <div class="w-full mt-4 flex justify-between items-center gap-3">
                                        <button type="button" @click="closeModal()"
                                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded">
                                            Batal
                                        </button>

                                        <button type="button" @click="exportHasilUjian(form)"
                                            :disabled="formExport.isExporting"
                                            class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-60 disabled:cursor-not-allowed">
                                            <span v-if="!formExport.isExporting">Export {{ exportFormatLabel }}</span>
                                            <span v-else>Memproses...</span>
                                        </button>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    </div>
</template>


<script>
import SortableTableTail from '@/components/global/SortableTableTail.vue';
import SkeletonTable from '@/components/global/SkeletonTable.vue';
import TanggalIndo from '@/components/global/TanggalIndo.vue';
import toastService from '../../helper/toastService';
import RequestManager from '../../helper/RequestManager';
import Swal from 'sweetalert2';
import PaginationTail from '@/components/global/PaginationTail.vue';
import Loading from '../../global/Loading.vue';

const PAGE_URL = "_a/course/event";
export default {
    data() {
        return {
            items: [],
            itemsPeserta: [],
            optionModule: [],
            pesertaList: [],
            loadingView: false,
            loadingPesertLiat: false,
            form: {
                id: null,
                nama_event: "",
                deskripsi: "",
                tanggal_event: "",

                files: [

                ]
            },
            imageUrl: "",
            isDragging: false,
            isEdit: false,
            showModal: false,
            showModalPesertaEvent: false,
            showModalPesertaList: false,
            showModalGenerateSoal: false,
            showModalExportSoal: false,
            search: "",
            searchPeserta: "",
            isLoadingKantor: false,
            pagination: {
                per_page: 10,
            },

            formExport: {
                exportFormat: "excel", // default
                isExporting: false,
                exportError: "",
                abortController: null,
            },

            paginationPesertaList: {
                per_page: 10,
            },
            pesertaEvent: {
                idevent: null,
                per: "Pegawai",
                modul: "",
                modeImport: "Manual",
                fileImport: null,
                waktu_mulai_ujian: "",
                peserta: []
            },
            pesertaEdit: {

            },
            selectedModul: null,
            columns: [
                { label: "Nama", key: "nama_event", sortable: true },
                { label: "Tanggal Event", key: "tanggal_event", sortable: true },
                { label: "Deskripsi", key: "deskripsi", sortable: true },
                { label: "Files", key: "file_event", sortable: true },
                { label: "Peserta", key: "jumlah_peserta", sortable: true },
                { label: "Updated", key: "created_at" }
            ],

        };
    },
    components: {
        SortableTableTail,
        SkeletonTable,
        TanggalIndo,
        PaginationTail,
        Loading
    },

    mounted() {
        this.viewItem();
        this.viewModul();
    },

    computed: {
        tampilkanSoal() {

            return '';
        },

        fileAcceptImport() {
            return ".xlsx";

        },

        waktuBerakhirComputed() {
            if (!this.pesertaEvent.waktu_mulai_ujian || !this.selectedModul) return ''

            const menit = Number(this.selectedModul.lama_pengerjaan || 0)
            if (!menit) return ''

            // datetime-local -> "YYYY-MM-DDTHH:mm"
            const start = new Date(this.pesertaEvent.waktu_mulai_ujian)
            if (isNaN(start.getTime())) return ''

            const end = new Date(start.getTime() + menit * 60 * 1000)

            // balik lagi ke format datetime-local
            return this.toDatetimeLocal(end)
        },
        exportFormatLabel() {
            return this.formExport.exportFormat === "excel" ? "Excel" : "PDF";
        },
    },

    watch: {
        'search'(newVal, oldVal) {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.viewItem();
            }, 1500);
        },
        'searchPeserta'(newVal, oldVal) {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.viewPesertaList(1, this.pesertaEvent.idevent);
            }, 1500);
        },

        'pesertaEvent.per'(newVal, oldVal) {
            this.pesertaEvent.peserta = [];
            this.itemsPeserta = []
        },

        'pesertaEvent.modeImport'(newVal, oldVal) {
            this.pesertaEvent.peserta = [];
            this.itemsPeserta = []
        },

        'pesertaEvent.modul'(newVal) {
            const selected = this.optionModule.find(
                mod => mod.id === newVal
            )

            if (selected) {
                this.selectedModul = selected
            } else {
                this.selectedModul = null;
            }
        }

    },
    methods: {

        openModal() {
            this.resetForm();
            this.showModal = true
        },
        closeModal() {
            this.resetForm();
            this.showModal = false;
            this.showModalGenerateSoal = false;
            this.showModalExportSoal = false;
        },

        closeModalPeserta() {
            this.showModalPesertaEvent = false;
        },
        resetForm() {
            this.form = {
                id: null,
                nama_event: "",
                deskripsi: "",
                tanggal_event: "",
                files: [

                ]
            };


        },

        openModalPesertaList(event) {
            this.pesertaEvent.idevent = event.id;
            this.viewPesertaList(1, event.id);
            this.showModalPesertaList = true
        },
        closeModalPesertaList() {
            this.pesertaList = [];
            this.showModalPesertaList = false
        },


        toDatetimeLocal(dateObj) {
            const pad = (n) => String(n).padStart(2, '0')
            const yyyy = dateObj.getFullYear()
            const mm = pad(dateObj.getMonth() + 1)
            const dd = pad(dateObj.getDate())
            const hh = pad(dateObj.getHours())
            const mi = pad(dateObj.getMinutes())
            return `${yyyy}-${mm}-${dd}T${hh}:${mi}`
        }
        ,

        removeFile(index) {
            this.form.files.splice(index, 1);
        },

        handleDrop(e) {
            this.isDragging = false;

            if (!Array.isArray(this.form.files)) {
                this.form.files = [];
            }

            const droppedFiles = Array.from(e.dataTransfer.files);
            this.form.files.push(...droppedFiles);
        },

        handleFileSelect(e) {
            if (!Array.isArray(this.form.files)) {
                this.form.files = [];
            }

            const selectedFiles = Array.from(e.target.files);
            this.form.files.push(...selectedFiles);
        },



        toEdit(row) {
            this.form = {
                id: row.id,
                nama_event: row.nama_event,
                deskripsi: row.deskripsi,
                tanggal_event: row.tanggal_event,
                files: []
            }
            this.showModal = true
        },

        editPeserta(peserta) {


        },

        toImportPeserta(row) {
            this.form.nama_event = row.nama_event;
            this.pesertaEvent.idevent = row.id;

            this.showModalPesertaEvent = true;
        },


        toGenerateSoalMasal(row) {
            this.showModalGenerateSoal = true;
            this.form = row;
        },

        toExportSoalMasal(row) {
            this.showModalExportSoal = true;
            this.form = row;
        },

        async viewItem(page = 1) {
            this.loadingView = true;
            try {
                let parameter = {
                    action: "view",
                    page: page,
                    per_page: this.pagination.per_page,
                    search: this.search
                }
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type === "success") {
                    this.items = response.data.data.data;
                    this.pagination = response.data.data;
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
                this.loadingView = false;
            } catch (error) {
                this.loadingView = false;
                toastService.show("Error", error, "error");
            }
        },

        async viewPesertaList(page = 1, idevent) {
            if (!idevent) {
                idevent = this.pesertaEvent.idevent
            };
            this.loadingPesertLiat = true;
            try {
                let parameter = {
                    action: "viewListPeserta",
                    page: page,
                    eventid: idevent,
                    per_page: this.paginationPesertaList.per_page,
                    search: this.searchPeserta
                }

                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type === "success") {
                    this.pesertaList = response.data.data.data;
                    this.paginationPesertaList = response.data.data;
                    this.loadingPesertLiat = false;
                } else {
                    toastService.show("Error", response.data.message, "error");
                    this.loadingPesertLiat = false;
                }
            }
            catch (error) {
                this.loadingPesertLiat = false;
                toastService.show("Error", error, "error");
            }
        },


        async viewModul() {
            try {
                let parameter = {
                    action: "viewModul",
                }
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type === "success") {
                    this.optionModule = response.data.data;
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", error, "error");
            }
        },

        // async createItem() {

        //     try {
        //         let formData = new FormData();
        //         formData.append("action", "store");
        //         formData.append("data", JSON.stringify(this.form));
        //         for (let i = 0; i < this.form.files.length; i++) {
        //             formData.append('files[]', this.form.files[i]);
        //         }

        //         const response = await RequestManager(PAGE_URL, formData);
        //         if (response.data.type === "success") {
        //             toastService.show("Success", response.data.message);
        //             this.closeModal();
        //             this.viewItem();
        //         } else {
        //             toastService.show("Error", response.data.message, "error");
        //         }
        //     } catch (error) {
        //         toastService.show("Error", error, "error");
        //     }
        // },



        async createItem() {
            try {
                const saveRes = await RequestManager(PAGE_URL, {
                    action: "store_metadata",  // buat action baru di backend
                    data: JSON.stringify(this.form),
                });

                if (saveRes.data.type !== "success") {
                    toastService.show("Error", saveRes.data.message, "error");
                    return;
                }

                const eventId = saveRes.data.data.id;

                // B. Minta signed URL untuk tiap file
                const files = this.form.files || [];
                const signRes = await RequestManager(PAGE_URL, {
                    action: "signed_urls",
                    event_id: eventId,
                    files: files.map(f => ({
                        name: f.name,
                        contentType: f.type || "application/octet-stream",
                    })),
                });

                if (signRes.data.type !== "success") {
                    toastService.show("Error", signRes.data.message, "error");
                    return;
                }

                const uploads = signRes.data.data.uploads; // [{url, object, name, contentType}]

                // C. Upload langsung ke GCS via PUT (tanpa lewat Laravel)
                for (let i = 0; i < uploads.length; i++) {
                    const u = uploads[i];
                    const file = files[i];

                    const put = await fetch(u.url, {
                        method: "PUT",
                        headers: {
                            "Content-Type": u.contentType,
                        },
                        body: file,
                    });

                    if (!put.ok) {
                        throw new Error(`Upload gagal untuk ${u.name}. HTTP ${put.status}`);
                    }
                }

                // D. Finalize: simpan path file ke DB
                const finalizeRes = await RequestManager(PAGE_URL, {
                    action: "finalize_upload",
                    event_id: eventId,
                    files: uploads.map(u => ({ path: u.object, name: u.name })),
                });

                if (finalizeRes.data.type === "success") {
                    toastService.show("Success", finalizeRes.data.message);
                    this.closeModal();
                    this.viewItem();
                } else {
                    toastService.show("Error", finalizeRes.data.message, "error");
                }

            } catch (error) {
                toastService.show("Error", error?.message || String(error), "error");
            }
        },

        async setPesertaEvent() {
            try {
                let formData = new FormData();
                formData.append("action", "setPesertaEvent");
                formData.append("data", JSON.stringify(this.pesertaEvent));
                formData.append('filesImport', this.pesertaEvent.fileImport);
                const response = await RequestManager(PAGE_URL, formData);
                if (response.data.type === "success") {
                    toastService.show("Success", response.data.message);
                    this.closeModalPeserta();
                    this.viewItem();
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", error, "error");
            }
        },


        async deleteItem(id) {
            const result = await Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Anda tidak akan bisa mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Merah untuk Delete
                cancelButtonColor: '#3085d6', // Biru
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });

            // 2. Cek Hasil Konfirmasi
            if (result.isConfirmed) {
                try {
                    let parameter = {
                        action: "destroy",
                        id: id
                    }

                    // Lanjutkan proses penghapusan ke API
                    const response = await RequestManager(PAGE_URL, parameter);

                    if (response.data.type === "success") {
                        // Tampilkan notifikasi sukses dan refresh data
                        toastService.show("Terhapus!", response.data.message, "success");
                        this.viewItem();
                    } else {
                        // Tampilkan notifikasi error dari server
                        toastService.show("Error", response.data.message, "error");
                    }

                } catch (error) {
                    // Tampilkan notifikasi error umum
                    toastService.show("Error", error.message || "Gagal menghubungi server", "error");
                }
            }
        },


        async deletePeserta(id, eventid) {
            const result = await Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Anda tidak akan bisa mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Merah untuk Delete
                cancelButtonColor: '#3085d6', // Biru
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });

            // 2. Cek Hasil Konfirmasi
            if (result.isConfirmed) {
                try {
                    let parameter = {
                        action: "deletePesertaById",
                        idsesi: id
                    }

                    // Lanjutkan proses penghapusan ke API
                    const response = await RequestManager(PAGE_URL, parameter);

                    if (response.data.type === "success") {
                        // Tampilkan notifikasi sukses dan refresh data
                        toastService.show("Terhapus!", response.data.message, "success");
                        this.viewPesertaList(1, eventid);
                        this.viewItem();
                    } else {
                        // Tampilkan notifikasi error dari server
                        toastService.show("Error", response.data.message, "error");
                    }

                } catch (error) {
                    // Tampilkan notifikasi error umum
                    toastService.show("Error", error.message || "Gagal menghubungi server", "error");
                }
            }
        },

        async get_cloud_url(id, path) {
            let parameter = {
                action: "get_cloud_url",
                id: id,
                path: path
            };
            const response = await RequestManager(
                PAGE_URL,
                parameter
            )
                .then((response) => {
                    if (response.data.type == "success") {
                        const cloudUrl = response.data.data;
                        if (cloudUrl) {
                            window.open(cloudUrl, "_blank");
                        }
                    } else {
                        toastService.show("Message", response.data.message);
                    }
                    this.load_cloud_url = null;
                })
                .catch((err) => {
                    this.load_cloud_url = null;
                });
        },



        onSearchKantor(search, loading, mode) {
            mode = this.pesertaEvent.per;
            if (search.length < 3) {
                this.dataResults = [];
                return;
            }
            this.isLoading = true;
            if (this.debounceTimeout) {
                clearTimeout(this.debounceTimeout);
            }

            this.debounceTimeout = setTimeout(() => {
                this.fetchKantor(search, mode);
            }, 500);
        },
        async fetchKantor(query, mode) {
            let endpoint = "global-attribute/kantor";
            let action = "";
            if (mode == "Pegawai") {
                action = "Pegawai";
            } else if (mode == "Kantor") {
                action = "view";
            }
            try {
                let parameter = {
                    action: action,
                    search: query
                }


                const response = await RequestManager(endpoint, parameter);
                if (response.data.type === "success") {
                    const newData = response.data.data;
                    const combined = [...this.itemsPeserta, ...newData];
                    this.itemsPeserta = Array.from(new Map(combined.map(item => [item.id, item])).values());

                } else {
                    toastService.show("Error", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", error, "error");
            }
        },


        handleFileSoalChange(event) {
            let filePeserta = event.target.files[0];
            if (filePeserta) {
                this.pesertaEvent.fileImport = filePeserta;
            }
        },

        async generateSoalMasal() {
            try {
                let parameter = {
                    action: "generateSoalMasal",
                    idevent: this.form.id,
                }
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type === "success") {
                    this.showModalGenerateSoal = false;
                    toastService.show("Success", response.data.message);
                    this.closeModal();
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", error, "error");
            }
        },

        async exportHasilUjian(row) {
            try {
                let parameter = {
                    action: "exportHasilUjian",
                    idevent: this.form.id,
                    exportFormat: this.formExport.exportFormat
                }
                this.formExport.isExporting = true;
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type === "success") {
                    this.showModalGenerateSoal = false;
                    toastService.show("Success", response.data.message);
                    this.closeModal();
                    this.$router.push("/course/export");
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
                this.formExport.isExporting = false;
            } catch (error) {
                this.formExport.isExporting = false;
                toastService.show("Error", error, "error");
            }
        },

        async generateTemplate(form) {
            if (!form.modul) {
                toastService.show("Perhatian", "Harap pilih modul ujian", "warning");
                return;
            }

            try {
                let parameter = {
                    action: "exportTemplatePeserta",
                    idevent: form.idevent,
                    modul: form.modul
                }
                this.formExport.isExporting = true;
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type === "success") {
                    this.showModalGenerateSoal = false;
                    toastService.show("Success", response.data.message);
                    this.closeModal();
                    this.$router.push("/course/export");
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
                this.formExport.isExporting = false;
            } catch (error) {
                this.formExport.isExporting = false;
                toastService.show("Error", error, "error");
            }

        }

    },
};
</script>

<style scoped>
/* Optional: custom styles */
</style>
