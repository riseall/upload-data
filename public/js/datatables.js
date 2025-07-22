const dataTableInstances = {};

document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem("access_token");

    // Function to destroy existing DataTable instance if it exists
    function destroyDataTable(tableId) {
        if (dataTableInstances[tableId]) {
            dataTableInstances[tableId].destroy();
            delete dataTableInstances[tableId];
        }
    }

    // General function to fetch and display data in a table
    async function fetchDataAndDisplay(
        endpoint,
        tableId,
        columnsConfig,
        fallbackMessage
    ) {
        const tableElement = document.getElementById(tableId);
        const tableBody = tableElement
            ? tableElement.querySelector("tbody")
            : null;
        const loadingRow = document.getElementById(`loading-row-${tableId}`);

        // if (!tableElement || !tableBody || !loadingRow) {
        //     console.error(
        //         `Elemen tabel, tbody, atau baris loading dengan ID '${tableId}' tidak ditemukan.`
        //     );
        //     return;
        // }

        // Show loading spinner
        loadingRow.style.display = "table-row";
        tableBody.innerHTML = ""; // Clear existing data while loading
        tableBody.appendChild(loadingRow);

        // Calculate colspan based on columnsConfig length + 1 for 'No.' column
        const colspan = columnsConfig.length + 1;
        loadingRow.querySelector("td").setAttribute("colspan", colspan);

        if (!token) {
            loadingRow.style.display = "none"; // Hide loading row
            tableBody.innerHTML = `<tr><td colspan="${colspan}" class="text-center">Anda belum terotentikasi.</td></tr>`;
            // window.location.href = '/login'; // Jangan redirect di sini, biarkan global auth handle
            return;
        }

        try {
            const response = await axios.get(
                `http://csv-uploader.test/api/${endpoint}`,
                {
                    // Ganti dengan URL API backend Anda
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            );

            const data = response.data;

            destroyDataTable(tableId); // Hancurkan instance DataTable yang lama sebelum mengisi data baru

            if (data && data.length > 0) {
                tableBody.innerHTML = ""; // Clear loading
                data.forEach((item, index) => {
                    const row = document.createElement("tr");
                    let rowHtml = `<td>${index + 1}</td>`; // For 'No.' column
                    columnsConfig.forEach((col) => {
                        let cellValue = item[col.key];
                        // Basic date formatting for 'created_at', 'updated_at', 'tanggal', 'expired_date', etc.
                        if (
                            col.key.includes("_at") ||
                            col.key.includes("tanggal") ||
                            col.key.includes("date") ||
                            col.key.includes("tgl")
                        ) {
                            if (cellValue) {
                                try {
                                    const dateObj = new Date(cellValue);
                                    if (!isNaN(dateObj.getTime())) {
                                        // Check for valid date
                                        // Format as YYYY-MM-DD
                                        const year = dateObj.getFullYear();
                                        const month = String(
                                            dateObj.getMonth() + 1
                                        ).padStart(2, "0"); // Months are 0-indexed
                                        const day = String(
                                            dateObj.getDate()
                                        ).padStart(2, "0");
                                        cellValue = `${year}-${month}-${day}`;
                                    }
                                } catch (e) {
                                    console.warn(
                                        `Invalid date format for key ${col.key}: ${cellValue}`
                                    );
                                }
                            }
                        }
                        rowHtml += `<td>${
                            cellValue !== undefined && cellValue !== null
                                ? cellValue
                                : "N/A"
                        }</td>`;
                    });
                    row.innerHTML = rowHtml;
                    tableBody.appendChild(row);
                });

                // Inisialisasi DataTable setelah data dimuat
                dataTableInstances[tableId] = $(`#${tableId}`).DataTable({
                    // Opsi DataTables, sesuaikan jika diperlukan
                    destroy: true, // Pastikan bisa dihancurkan jika dipanggil lagi
                    paging: true,
                    searching: true,
                    info: true,
                    autoWidth: true,
                    // responsive: true, // Aktifkan jika Anda ingin responsif
                });

                dataTableInstances[tableId].columns.adjust().draw();
            } else {
                tableBody.innerHTML = `<tr><td colspan="${colspan}" class="text-center">Data ${fallbackMessage} masih kosong.</td></tr>`;
                // Jika tidak ada data, DataTables tidak perlu diinisialisasi
            }
        } catch (error) {
            console.error(`Gagal mengambil data ${fallbackMessage}:`, error);
            let errorMessage = `Gagal memuat ${fallbackMessage}.`;
            if (error.response && error.response.status === 401) {
                errorMessage =
                    "Sesi Anda telah berakhir atau token tidak valid. Silakan login kembali.";
                localStorage.removeItem("access_token");
                localStorage.removeItem("user_name");
                window.location.href = "/login";
            } else if (
                error.response &&
                error.response.data &&
                error.response.data.message
            ) {
                errorMessage = error.response.data.message;
            }
            tableBody.innerHTML = `<tr><td colspan="${colspan}" class="text-center">${errorMessage}</td></tr>`;
        } finally {
            loadingRow.style.display = "none";
        }
    }

    // Define column configurations for each table
    // Key harus sesuai dengan nama properti JSON yang dikembalikan oleh API backend
    const masterProductColumns = [
        { header: "Kode Barang METD", key: "kode_brg_metd" },
        { header: "Kode Barang PH", key: "kode_brg_ph" },
        { header: "Nama Barang METD", key: "nama_brg_metd" },
        { header: "Nama Barang PH", key: "nama_brg_ph" },
        { header: "Satuan METD", key: "satuan_metd" },
        { header: "Satuan PH", key: "satuan_ph" },
        { header: "Konversi Kuantitas", key: "konversi_qty" },
        { header: "Tanggal Upload", key: "created_at" },
        // { header: 'Updated At', key: 'updated_at' }
    ];

    const masterCustomerColumns = [
        { header: "Id Outlet", key: "id_outlet" },
        { header: "Nama Outlet", key: "nama_outlet" },
        { header: "Cabang PH", key: "cbg_ph" },
        { header: "Kode Cabang PH", key: "kode_cbg_ph" },
        { header: "Cabang METD", key: "cbg_metd" },
        { header: "Alamat 1", key: "alamat_1" },
        { header: "Alamat 2", key: "alamat_2" },
        { header: "Alamat 3", key: "alamat_3" },
        { header: "No. Telepon", key: "no_telp" },
        { header: "Tanggal Upload", key: "created_at" },
        // { header: 'Updated At', key: 'updated_at' }
    ];

    const stockMetdColumns = [
        { header: "Kode Barang METD", key: "kode_brg_metd" },
        { header: "Kode Barang PH", key: "kode_brg_ph" },
        { header: "Nama Barang METD", key: "nama_brg_metd" },
        { header: "Nama Barang PH", key: "nama_brg_ph" },
        { header: "Plant", key: "plant" },
        { header: "Nama Plant", key: "nama_plant" },
        { header: "Suhu Gudang Penyimpanan", key: "suhu_gudang_penyimpanan" },
        { header: "Batch PH", key: "batch_phapros" }, // Pastikan ini sesuai dengan nama kolom di DB/API
        { header: "Expired Date", key: "expired_date" },
        { header: "Satuan METD", key: "satuan_metd" },
        { header: "Satuan PH", key: "satuan_phapros" }, // Pastikan ini sesuai dengan nama kolom di DB/API
        { header: "Harga Beli", key: "harga_beli" },
        { header: "Konversi Qty", key: "konversi_qty" },
        { header: "Qty On Hand", key: "qty_onhand_metd" },
        { header: "Qty Sellable", key: "qty_selleable" },
        { header: "Qty Non Sellable", key: "qty_non_selleable" },
        { header: "Qty Intransit In", key: "qty_intransit_in" },
        { header: "Nilai Intransit In", key: "nilai_intransit_in" },
        { header: "Qty Intransit Pass", key: "qty_intransit_pass" },
        { header: "Nilai Intransit Pass", key: "nilai_intransit_pass" },
        { header: "Tanggal Terima Barang", key: "tgl_terima_brg" },
        { header: "Source Beli", key: "source_beli" },
        { header: "Tanggal Upload", key: "created_at" },
        // { header: 'Updated At', key: 'updated_at' }
    ];

    const sellOutFakturColumns = [
        { header: "Kode Cabang PH", key: "kode_cbg_ph" },
        { header: "Cabang PH", key: "cbg_ph" },
        { header: "Tanggal Faktur", key: "tgl_faktur" },
        { header: "Id Outlet", key: "id_outlet" },
        { header: "No Faktur", key: "no_faktur" },
        // { header: "No Invoice", key: "no_invoice" },
        // { header: "Status", key: "status" },
        { header: "Nama Outlet", key: "nama_outlet" },
        { header: "Alamat 1", key: "alamat_1" },
        { header: "Alamat 2", key: "alamat_2" },
        { header: "Alamat 3", key: "alamat_3" },
        { header: "Kode Barang METD", key: "kode_brg_metd" },
        { header: "Kode Barang PH", key: "kode_brg_phapros" }, // Pastikan ini sesuai
        { header: "Nama Barang METD", key: "nama_brg_metd" },
        { header: "Satuan METD", key: "satuan_metd" },
        { header: "Satuan PH", key: "satuan_ph" },
        { header: "Qty", key: "qty" },
        { header: "Konversi Qty", key: "konversi_qty" },
        { header: "HNA", key: "hna" },
        // { header: "Diskon Dimuka (%)", key: "diskon_dimuka_persen" },
        // { header: "Diskon Dimuka", key: "diskon_dimuka_amount" },
        { header: "Diskon 1 (%)", key: "diskon_persen_1" },
        { header: "Diskon 1", key: "diskon_ammount_1" },
        { header: "Diskon 2 (%)", key: "diskon_persen_2" },
        { header: "Diskon 2", key: "diskon_ammount_2" },
        { header: "Total Diskon (%)", key: "total_diskon_persen" },
        { header: "Total Diskon", key: "total_diskon_ammount" },
        { header: "Netto", key: "netto" },
        { header: "Brutto", key: "brutto" },
        { header: "Ppn", key: "ppn" },
        { header: "Jumlah", key: "jumlah" },
        { header: "Segmen", key: "segmen" },
        { header: "SO Number", key: "so_number" },
        { header: "No Shipper", key: "no_shipper" },
        { header: "No PO", key: "no_po" },
        { header: "Batch PH", key: "batch_ph" },
        { header: "Expired Date", key: "exp_date" },
        { header: "Tanggal Upload", key: "created_at" },
        // { header: 'Updated At', key: 'updated_at' }
    ];

    const sellOutNonfakturColumns = [
        { header: "Kode Cabang PH", key: "kode_cbg_ph" },
        { header: "Cabang PH", key: "cbg_ph" },
        { header: "Tanggal Transaksi", key: "tgl_transaksi" },
        { header: "Id Outlet", key: "id_outlet" },
        // { header: "No Invoice", key: "no_invoice" },
        // { header: "Status", key: "status" },
        { header: "Nama Outlet", key: "nama_outlet" },
        { header: "Alamat 1", key: "alamat_1" },
        { header: "Alamat 2", key: "alamat_2" },
        { header: "Alamat 3", key: "alamat_3" },
        { header: "Kode Barang METD", key: "kode_brg_metd" },
        { header: "Kode Barang PH", key: "kode_brg_phapros" },
        { header: "Nama Barang METD", key: "nama_brg_metd" },
        { header: "Satuan METD", key: "satuan_metd" },
        { header: "Satuan PH", key: "satuan_ph" },
        { header: "Qty", key: "qty" },
        { header: "Konversi Qty", key: "konversi_qty" },
        { header: "HNA", key: "hna" },
        // { header: "Diskon Dimuka (%)", key: "diskon_dimuka_persen" },
        // { header: "Diskon Dimuka", key: "diskon_dimuka_amount" },
        { header: "Diskon 1 (%)", key: "diskon_persen_1" },
        { header: "Diskon 1", key: "diskon_ammount_1" },
        { header: "Diskon 2 (%)", key: "diskon_persen_2" },
        { header: "Diskon 2", key: "diskon_ammount_2" },
        { header: "Total Diskon (%)", key: "total_diskon_persen" },
        { header: "Total Diskon", key: "total_diskon_ammount" },
        { header: "Netto", key: "netto" },
        { header: "Brutto", key: "brutto" },
        { header: "Ppn", key: "ppn" },
        { header: "Jumlah", key: "jumlah" },
        { header: "Segmen", key: "segmen" },
        { header: "SO Number", key: "so_number" },
        { header: "No Shipper", key: "no_shipper" },
        { header: "No PO", key: "no_po" },
        { header: "Batch PH", key: "batch_ph" },
        { header: "Expired Date", key: "exp_date" },
        { header: "Tanggal Upload", key: "created_at" },
        // { header: 'Updated At', key: 'updated_at' }
    ];

    // Mapping tab ID ke endpoint dan konfigurasi kolom
    const tabConfigs = {
        "mstrProd-tab": {
            endpoint: "master-products",
            tableId: "prodTable",
            columns: masterProductColumns,
            fallback: "Master Product",
        },
        "cust-tab": {
            endpoint: "master-customers",
            tableId: "custTable",
            columns: masterCustomerColumns,
            fallback: "Master Customer",
        },
        "stock-tab": {
            endpoint: "stock-metd",
            tableId: "stockTable",
            columns: stockMetdColumns,
            fallback: "Stock METD",
        },
        "faktur-tab": {
            endpoint: "sellout-faktur",
            tableId: "fakturTable",
            columns: sellOutFakturColumns,
            fallback: "Sellout dengan Faktur",
        },
        "nonfaktur-tab": {
            endpoint: "sellout-nonfaktur",
            tableId: "nonfakturTable",
            columns: sellOutNonfakturColumns,
            fallback: "Sellout tanpa Faktur",
        },
    };

    // Fungsi untuk memuat data untuk tab yang aktif
    async function loadActiveTabData() {
        const activeTabButton = document.querySelector(
            "#myTab .nav-link.active"
        );
        if (activeTabButton) {
            const tabConfig = tabConfigs[activeTabButton.id];
            if (tabConfig) {
                await fetchDataAndDisplay(
                    tabConfig.endpoint,
                    tabConfig.tableId,
                    tabConfig.columns,
                    tabConfig.fallback
                );
            }
        }
    }

    // Panggil fungsi untuk tab aktif saat halaman dimuat pertama kali
    loadActiveTabData();

    // Tambahkan event listener untuk setiap tombol tab
    const tabButtons = document.querySelectorAll("#myTab .nav-link");
    tabButtons.forEach((button) => {
        button.addEventListener("shown.bs.tab", async function (event) {
            const tabConfig = tabConfigs[event.target.id];
            if (tabConfig) {
                await fetchDataAndDisplay(
                    tabConfig.endpoint,
                    tabConfig.tableId,
                    tabConfig.columns,
                    tabConfig.fallback
                );
            }
        });
    });
});
