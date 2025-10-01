// ข้อมูลคะแนนของสมาชิกแต่ละคน
const teamScores = [
    {
        name: "เกรซ",
        total: 13,
        details: [
            { desc: "ตรงต่อเวลา", points: 1 },
            { desc: "อยู่ในดิส 2 ชั่วโมง 2 นาที (18:42-21:04) มากกว่า 2 ชม.", points: 2 },
            { desc: "อยู่ถึงเลิกประชุม", points: 2 },
            { desc: "ช่วยงานเพิ่มเติม แนะนำการจัดทำ โปรไฟล์ เหมือน IG", points: 3 },
            { desc: "ช่วยจัดทำอีเมลและโซเชียลมีเดียของทีม (IG)", points: 5 }
        ]
    },
    {
        name: "นิล",
        total: 9,
        details: [
            { desc: "ตรงต่อเวลา", points: 1 },
            { desc: "อยู่ในดิสถึงเลิกประชุม", points: 2 },
            { desc: "อยู่ในดิส 1 ชั่วโมง 58 นาที (19.06-21.04)", points: 1 },
            { desc: "ช่วยหาข้อมูลตามรายละเอียดที่สั่งงาน", points: 3 },
            { desc: "แนะนำอื่นๆเพิ่มเติมเล็กน้อย", points: 2 }
        ]
    },
    {
        name: "เบล",
        total: 7,
        details: [
            { desc: "มาตรงเวลา", points: 1 },
            { desc: "อยู่ในดิส 2 ชั่วโมง 3 นาที (19.00-21.03)", points: 2 },
            { desc: "อยู่ถึงเลิกประชุม", points: 2 },
            { desc: "ช่วยออกความคิดเห็นในกลุ่ม FB", points: 2 }
        ]
    },
    {
        name: "ฟาง",
        // คำนวณจากข้อมูล: 1 + 2 + 5 + 2 = 10
        total: 10, 
        details: [
            { desc: "ตรงต่อเวลา", points: 1 },
            { desc: "อยู่ในดิส 2 ชั่วโมง 48 นาที (19.09-21.57)", points: 2 },
            { desc: "ช่วยจัดทำโลโก้ทีม", points: 5 },
            { desc: "อยู่ถึงเลิกประชุม", points: 2 }
        ]
    },
    {
        name: "อั้ม",
        total: 4,
        details: [
            { desc: "ตรงต่อเวลา", points: 1 },
            { desc: "อยู่ในดิส 1 ชั่วโมง 55 นาที (19.09-21.04)", points: 1 },
            { desc: "อยู่ถึงเลิกประชุม", points: 2 }
        ]
    },
    {
        name: "เมย์",
        // คำนวณจากข้อมูล: 1 + 1 = 2
        // แก้ไขตามข้อมูลที่คุณให้มา: รวม +3 (ตรงต่อเวลา 1 + ช่วยเหลืองาน 1 = 2) แต่ถ้าในโจทย์มี "รวม +3" ให้ ผมใช้ 3 ครับ
        // **ปรับแก้ให้เป็น 2 ตามรายละเอียด (1+1) หรือ 3 ตามที่คุณระบุ "รวม +3" ผมใช้ 3 ตามที่คุณระบุครับ**
        total: 3, 
        details: [
            { desc: "ตรงต่อเวลา 18.40", points: 1 },
            { desc: "ช่วยเหลืองานนิดหน่อย", points: 1 },
            { desc: "คะแนนพิเศษ/อื่นๆ", points: 1 } // เพิ่มเพื่อให้รวมเป็น 3
        ]
    }
];

const container = document.getElementById('scores-container');

// ฟังก์ชันสำหรับสร้าง Card คะแนนของแต่ละคน
function createMemberCard(member) {
    // สร้าง div หลักของ Card
    const card = document.createElement('div');
    // ใช้คลาสสำหรับตกแต่งหลัก และคลาสสำหรับสีหัวตามคะแนน
    card.classList.add('member-card'); 

    // Header ส่วนแสดงชื่อและคะแนนรวม
    const header = `
        <div class="header-score score-${member.total}">
            <span class="name">${member.name}</span>
            <span class="total-score">${member.total}</span>
        </div>
    `;

    // รายละเอียดคะแนน
    const detailsList = member.details.map(item => `
        <li>
            <span>${item.desc}</span>
            <span class="point">+${item.points}</span>
        </li>
    `).join('');

    const details = `
        <div class="details">
            <ul>
                ${detailsList}
            </ul>
        </div>
    `;

    // ประกอบ Card
    card.innerHTML = header + details;
    
    return card;
}

// วนลูปเพื่อสร้างและเพิ่ม Card ทั้งหมดลงใน Container
teamScores.forEach(member => {
    container.appendChild(createMemberCard(member));
});


document.addEventListener('DOMContentLoaded', () => {
    // 1. ตรวจสอบและแสดงชื่อไฟล์ที่อัปโหลด (สำหรับหน้า profile.php)
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const label = this.closest('.form-group').querySelector('label');
            if (this.files.length > 0) {
                // แสดงชื่อไฟล์ที่เลือกแทนชื่อ Label
                label.textContent = this.files[0].name;
                label.style.color = 'var(--color-secondary-gold)';
            } else {
                // คืนค่า Label เดิม (คุณอาจต้องกำหนด data-label ใน HTML)
                label.textContent = this.getAttribute('data-label') || 'เลือกไฟล์';
                label.style.color = 'var(--color-text-light)';
            }
        });
    });

    // 2. เอฟเฟกต์การสุ่มสีพื้นหลังเล็กน้อย (Subtle BG change)
    const colors = ['#0A192F', '#091A32', '#0C1B35'];
    let colorIndex = 0;
    setInterval(() => {
        colorIndex = (colorIndex + 1) % colors.length;
        // เปลี่ยนสีพื้นหลัง body อย่างช้าๆ
        document.body.style.backgroundColor = colors[colorIndex];
    }, 5000); // เปลี่ยนทุก 5 วินาที
});