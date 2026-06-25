import os
import tkinter as tk
from tkinter import filedialog, messagebox, simpledialog
import pyzipper
import configparser
from cryptography.fernet import Fernet

# Secure Configurations
CONFIG_FILE = "set.ini"
ZIP_NAME = "secured_files.zip"
KEY_FILE = "key.key"

# Generate or Load Encryption Key
if not os.path.exists(KEY_FILE):
    key = Fernet.generate_key()
    with open(KEY_FILE, 'wb') as f:
        f.write(key)
else:
    with open(KEY_FILE, 'rb') as f:
        key = f.read()

cipher = Fernet(key)

# Encryption & Decryption Functions
def encrypt_data(data):
    return cipher.encrypt(data.encode()).decode()

def decrypt_data(data):
    try:
        return cipher.decrypt(data.encode()).decode()
    except:
        return None

# Read & Write Configuration Without Overwriting Existing Values
def save_security_details(password=None, question=None, answer=None, new_files=None):
    config = configparser.ConfigParser()
    if os.path.exists(CONFIG_FILE):
        config.read(CONFIG_FILE)

    if "Security" not in config:
        config["Security"] = {}

    if password:
        config["Security"]["password"] = encrypt_data(password)
    if question:
        config["Security"]["question"] = question
    if answer:
        config["Security"]["answer"] = answer

    # Preserve existing file list and append new ones
    existing_files = config["Security"].get("files", "")
    existing_file_list = existing_files.split(",") if existing_files else []

    if new_files:
        for file in new_files:
            if file not in existing_file_list:
                existing_file_list.append(file)

    config["Security"]["files"] = ",".join(existing_file_list)

    with open(CONFIG_FILE, 'w') as configfile:
        config.write(configfile)

def get_security_details():
    config = configparser.ConfigParser()
    if not os.path.exists(CONFIG_FILE):
        return None, None, None, None

    config.read(CONFIG_FILE)
    if "Security" not in config:
        return None, None, None, None

    encrypted_password = config.get("Security", "password", fallback=None)
    question = config.get("Security", "question", fallback=None)
    answer = config.get("Security", "answer", fallback=None)
    file_names = config.get("Security", "files", fallback="")

    decrypted_password = decrypt_data(encrypted_password) if encrypted_password else None
    file_list = file_names.split(",") if file_names else []

    return decrypted_password, question, answer, file_list

def create_protected_zip():
    selected_files = filedialog.askopenfilenames(title="Select Files to Add to ZIP")
    if not selected_files:
        messagebox.showwarning("Warning", "No files selected!")
        return

    password = password_entry.get()
    question = question_entry.get()
    answer = answer_entry.get()

    if not password or not question or not answer:
        messagebox.showwarning("Warning", "All fields (Password, Security Question, Answer) are required!")
        return

    existing_files = []
    
    if os.path.exists(ZIP_NAME):
        with pyzipper.AESZipFile(ZIP_NAME, 'r') as zf:
            existing_files = zf.namelist()

    # Append files without overwriting the existing ones
    with pyzipper.AESZipFile(ZIP_NAME, 'a', compression=pyzipper.ZIP_LZMA, encryption=pyzipper.WZ_AES) as zf:
        zf.setpassword(password.encode())
        for file in selected_files:
            if os.path.exists(file) and os.path.basename(file) not in existing_files:
                zf.write(file, os.path.basename(file))

    save_security_details(password=password, question=question, answer=answer, new_files=selected_files)
    messagebox.showinfo("Success", f"Files added to secured ZIP: {ZIP_NAME}")

def open_zip():
    if not os.path.exists(ZIP_NAME):
        messagebox.showerror("Error", "No secured ZIP file found!")
        return

    password = password_entry.get()
    if not password:
        messagebox.showwarning("Warning", "Enter the password to unlock the ZIP!")
        return

    try:
        with pyzipper.AESZipFile(ZIP_NAME, 'r') as zf:
            zf.setpassword(password.encode())
            file_names = zf.namelist()
        
        extract_path = filedialog.askdirectory(title="Select Extraction Folder")
        if not extract_path:
            messagebox.showwarning("Warning", "Extraction cancelled.")
            return

        with pyzipper.AESZipFile(ZIP_NAME, 'r') as zf:
            zf.setpassword(password.encode())
            zf.extractall(extract_path)

        messagebox.showinfo("Success", "Files extracted successfully!")
    except:
        messagebox.showerror("Error", "Incorrect password!")

def reset_password():
    _, question, answer, _ = get_security_details()

    if not question or not answer:
        messagebox.showerror("Error", "No security question found. Password reset not possible.")
        return

    user_answer = simpledialog.askstring("Security Question", f"{question}", show="*")
    if not user_answer or user_answer.strip().lower() != answer.strip().lower():
        messagebox.showerror("Error", "Incorrect answer. Password reset failed.")
        return

    new_password = simpledialog.askstring("New Password", "Enter a new password:", show="*")
    if not new_password:
        messagebox.showwarning("Warning", "Password cannot be empty!")
        return

    save_security_details(password=new_password)
    messagebox.showinfo("Success", "Password reset successful!")

# GUI Setup
root = tk.Tk()
root.title("🔒 Secure File Zipper")
root.geometry("450x350")
root.resizable(False, False)

_, saved_question, _, _ = get_security_details()
default_question = saved_question if saved_question else ""

# Header
tk.Label(root, text="🔒 Secure ZIP Creator", font=("Arial", 14, "bold")).pack(pady=10)

# Password
tk.Label(root, text="Set ZIP Password:").pack()
password_entry = tk.Entry(root, show="*", width=40)
password_entry.pack()

# Security Question
tk.Label(root, text="Security Question:").pack()
question_entry = tk.Entry(root, width=40)
question_entry.insert(0, default_question)
question_entry.pack()

# Answer
tk.Label(root, text="Answer:").pack()
answer_entry = tk.Entry(root, show="*", width=40)
answer_entry.pack()

# Buttons
button_frame = tk.Frame(root)
button_frame.pack(pady=10)

tk.Button(button_frame, text="📂 Add to Secure ZIP", command=create_protected_zip, width=20, bg="#4CAF50", fg="white").grid(row=0, column=0, padx=5)
tk.Button(button_frame, text="🔓 Open ZIP", command=open_zip, width=15, bg="#008CBA", fg="white").grid(row=0, column=1, padx=5)

tk.Button(root, text="❓ Forgot Password?", command=reset_password, width=20, bg="#f44336", fg="white").pack(pady=5)

root.mainloop()
