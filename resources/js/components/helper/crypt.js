import CryptoJS from "crypto-js";

export function enkripsi(data) {
  if (Array.isArray(data)) {
    data = JSON.stringify(data);
  }
  var key = CryptoJS.enc.Utf8.parse("K3vR8tY2Qn5XbM1c"); // Secret key
  var iv = CryptoJS.enc.Utf8.parse("X7mQ4aK9Lp2Zt6Yr"); //Vector iv
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
    var key = CryptoJS.enc.Utf8.parse("K3vR8tY2Qn5XbM1c"); // Secret key
    var iv = CryptoJS.enc.Utf8.parse("X7mQ4aK9Lp2Zt6Yr"); //Vector iv
    var decrypted = CryptoJS.AES.decrypt(encryptedString, key, {
      iv: iv,
      padding: CryptoJS.pad.Pkcs7,
    });

    return decrypted.toString(CryptoJS.enc.Utf8);
  } catch (error) {
    return {}
  }

}
