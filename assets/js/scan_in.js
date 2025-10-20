let streamIn = null;
let isScanningIn = false;
const beepSound = new Audio("/inventory-system/assets/sound/beep.mp3");

document.addEventListener("DOMContentLoaded", () => {
    startScanIn();
});

function startScanIn() {
    const video = document.getElementById("preview");
    const message = document.getElementById("message");

    if (!video || isScanningIn) return;

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        message.innerHTML = `<div class='alert alert-danger'>Browser tidak mendukung kamera API.</div>`;
        return;
    }

    navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
        .then(stream => {
            streamIn = stream;
            video.srcObject = stream;
            video.play();
            message.innerHTML = `<div class='alert alert-success'>✅ Kamera aktif, arahkan ke barcode untuk menambah stok.</div>`;
            isScanningIn = true;
            initScannerIn();
        })
        .catch(err => {
            message.innerHTML = `<div class='alert alert-danger'>❌ Kamera gagal diakses: ${err.message}</div>`;
        });
}

function stopScanIn() {
    if (streamIn) {
        streamIn.getTracks().forEach(track => track.stop());
        streamIn = null;
    }
    if (typeof Quagga !== "undefined") Quagga.stop();
    isScanningIn = false;
    const msg = document.getElementById("message");
    if (msg) msg.innerHTML = `<div class='alert alert-secondary'>⏹️ Kamera dimatikan.</div>`;
}

function initScannerIn() {
    const message = document.getElementById("message");
    if (typeof Quagga === "undefined") {
        message.innerHTML = `<div class='alert alert-danger'>❌ Quagga belum dimuat.</div>`;
        return;
    }

    Quagga.init({
        inputStream: {
            type: "LiveStream",
            target: document.querySelector("#scanner-container"),
            constraints: { facingMode: "user" }
        },
        decoder: { readers: ["code_128_reader", "ean_reader", "code_39_reader"] }
    }, err => {
        if (err) {
            message.innerHTML = `<div class='alert alert-danger'>Gagal memulai scanner: ${err}</div>`;
            return;
        }
        Quagga.start();
    });

    Quagga.onDetected(data => {
        const kode = data.codeResult.code;
        message.innerHTML = `<div class='alert alert-info'>📦 Membaca barcode: ${kode}</div>`;
        updateStok(kode, "in");
    });
}

function updateStok(kode, tipe) {
    const message = document.getElementById("message");
    fetch("/inventory-system/api/update_stok.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ kode, tipe })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            message.innerHTML = `<div class='alert alert-success'>✅ ${data.nama_barang} ditambahkan. Stok baru: ${data.stok_baru}</div>`;
        } else {
            message.innerHTML = `<div class='alert alert-warning'>⚠️ ${data.message}</div>`;
        }
    })
    .catch(() => {
        message.innerHTML = `<div class='alert alert-danger'>❌ Gagal mengupdate stok ke server.</div>`;
    });
}