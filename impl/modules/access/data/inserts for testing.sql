delete from IFCMACC_USERS;

INSERT INTO IFCMACC_USERS(USR_NAME, ACC_PWD, ACC_PWD_ALGRTHM, ACC_PWD_SALT, EMAIL_ADDR, STATE) VALUES 
('usuarioactivo', '03b8026ba5632b53020b5c99d97061ffe06001e2437bf29d173920e43823fbe2faa580d55f5194508d5159e23951e97973eb847366fb724768cab24049c4cfbf', 'sha512', '0PqGuXBpSKyqiLQI', 'tyler.information0@gmail.com', 128)/*passwd: accediendo*/,
('usuarioinactivo','03b8026ba5632b53020b5c99d97061ffe06001e2437bf29d173920e43823fbe2faa580d55f5194508d5159e23951e97973eb847366fb724768cab24049c4cfbf', 'sha512', '0PqGuXBpSKyqiLQI', 'tyler.information1@gmail.com', 1)/*passwd: accediendo*/,
('usuariobloqueado', '03b8026ba5632b53020b5c99d97061ffe06001e2437bf29d173920e43823fbe2faa580d55f5194508d5159e23951e97973eb847366fb724768cab24049c4cfbf', 'sha512', '0PqGuXBpSKyqiLQI', 'tyler.information2@gmail.com', 2)/*passwd: accediendo*/,
('usuarionoconfirm','03b8026ba5632b53020b5c99d97061ffe06001e2437bf29d173920e43823fbe2faa580d55f5194508d5159e23951e97973eb847366fb724768cab24049c4cfbf', 'sha512', '0PqGuXBpSKyqiLQI', 'tyler.information3@gmail.com', 4)/*passwd: accediendo*/;

