import CryptoJS from "crypto-js";

export function enkripsi(data) {
  if (Array.isArray(data)) {
    data = JSON.stringify(data);
  }
  var key = CryptoJS.enc.Utf8.parse("AddG4tKdmdBdzWdQ"); // Secret key
  var iv = CryptoJS.enc.Utf8.parse("aZ7hB1mP9xY4sD8L"); //Vector iv
  var encrypted = CryptoJS.AES.encrypt(data, key, {
    iv: iv,
    mode: CryptoJS.mode.CBC,
    padding: CryptoJS.pad.Pkcs7,
  });
  return encrypted.toString();
}

export function dekripsi(data) {

  let encryptedString = data.replace(/ /g, '+');
  try {
    var key = CryptoJS.enc.Utf8.parse("AddG4tKdmdBdzWdQ"); // Secret key
    var iv = CryptoJS.enc.Utf8.parse("aZ7hB1mP9xY4sD8L"); //Vector iv
    var decrypted = CryptoJS.AES.decrypt(encryptedString, key, {
      iv: iv,
      padding: CryptoJS.pad.Pkcs7,
    });

    return decrypted.toString(CryptoJS.enc.Utf8);
  } catch (error) {
    return {}
  }

}
