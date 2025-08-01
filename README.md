# XKCD Email Subscription System

## 📋 Overview
This assignment is for the **XKCD Email Subscription System** using **PHP**.

All required features have been implemented **inside the `src/` directory** as per the instructions.

---

## ✨ Implemented Features

### 1️⃣ Email Verification & Subscription
- Users can **register using their email** and receive a **6-digit numeric verification code**.
- Verified emails are stored in `registered_emails.txt`.

### 2️⃣ Unsubscribe Mechanism
- All outgoing emails include a working **Unsubscribe link**.
- Users can **unsubscribe by verifying a 6-digit code**, which removes their email from the system.

### 3️⃣ XKCD Comic Delivery (CRON Job)
- A **CRON job automatically runs every 24 hours** to:
  - Fetch a random XKCD comic using the XKCD API.
  - Send the comic as **formatted HTML emails** to all subscribed users.
  - Each email contains an **unsubscribe link**.

---

## 🛠 Technical Notes

- Emails tested using **Mailpit** at `http://localhost:8025/`.
- Emails are **HTML-formatted** (no JSON or plain text).
- **No database used**—email storage handled through a **simple text file** (`registered_emails.txt`).
- All required functions implemented inside `functions.php`.
- **CRON job setup included**:
  - `setup_cron.sh` to configure CRON.
  - `cron.php` to handle daily email sending.

---

## 📸 Screenshots & Video Demo (To Be Added)

1. ✅ Email Verification Form
![image](https://github.com/user-attachments/assets/e582fe58-c00f-4a15-b02d-22290678efb4)

2. ✅ Verification Code Input & Success Message
![image](https://github.com/user-attachments/assets/e3613e96-b251-45a3-8a67-918cad18cef1)


3. ✅ XKCD Comic Email Sample (Mailpit Screenshot)
![image](https://github.com/user-attachments/assets/c18e0fed-e0a7-4e53-ac1a-77517be704a6)


4. ✅ Unsubscribe Process Screens
![image](https://github.com/user-attachments/assets/0f5b1b2a-7373-4891-ae57-e98cc8db035c)


5. ✅ CRON Email Screenshot (or video)
![image](https://github.com/user-attachments/assets/342d4194-08ab-4d52-aaf8-c0833e70f7f9)


---

- Tested successfully in:
  - Browser: `http://localhost:8000/xkcd/src`
  - Mailpit SMTP: `localhost:1025` with web UI at `localhost:8025`.

---

## 📜 License

This project is for **personal/home use only**.  
For any collaboration, replication, or commercial adaptation, contact the author.

---

## ❤️ Acknowledgements

This is a solo passion project, built with countless nights of tinkering, testing, and debugging.  
If you find it useful or inspiring, feel free to ⭐ the repository or connect with me on social media!

---

> _“If the mind can create, the hands can translate.”_ – Hashtag
