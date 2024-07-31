

const now = new Date();
const year = now.getFullYear();
const month = String(now.getMonth() + 1).padStart(2, '0'); 
const day = String(now.getDate()).padStart(2, '0'); 
const hours = now.getHours();

function settings() {
    const access_date = `${year}${month}${day}`;
    const get_settings = localStorage.getItem('sv_settings');
    if (get_settings) {
        settingsData = JSON.parse(get_settings); 
        settingsData.last_access = access_date; 
        localStorage.setItem('sv_settings', JSON.stringify(settingsData));
    } else {
        settingsData = {
            user_id: user_id_generate(),
            user_name: 'gest',
            fase_access_detector: 'yes',
            fast_access: access_date,
            last_access: access_date,
            function_switch: {
                fn_se5: 'off',
                fn_if1: 'on',
            },
        };
        localStorage.setItem('sv_settings', JSON.stringify(settingsData));
    }


    return settingsData;
}


function user_id_generate() {
    // useridの生成
    const access_id = `${year}${month}${day}`;

    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789'; // 英数字の文字列
    const length = 10; // 生成したい文字列の長さ
    let random_id = '';

    for (let i = 0; i < length; i++) {
        random_id += chars[Math.floor(Math.random() * chars.length)];
    }

    const user_id = `${access_id}_${random_id}`;
    return user_id;
}
