export default {
    rupiah: function rupiah(angka) {
        var hasil_rupiah = "Rp " + angka.toFixed(0).replace(/\d(?=(\d{3})+$)/g, '$&.');
        return hasil_rupiah;
    },

    codeOrder: function codeOrder(length) {
        var result = '';
        var characters = '0123456789'; // karakter yang digunakan dalam kode

        for (var i = 0; i < length; i++) {
            var randomIndex = Math.floor(Math.random() * characters.length);
            result += characters.charAt(randomIndex);
        }

        return result;
    },

    makeReadable: function (key) {
        return this.capitalizeWords(key.replaceAll('_', ' ').split(' ')).join(' ');
    },
}