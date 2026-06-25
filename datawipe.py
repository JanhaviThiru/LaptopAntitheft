import zipfile
import pyzipper
import tkinter as tk
from tkinter import filedialog
import os

def select_files():
    """Open file dialog to select multiple files."""
    root = tk.Tk()
    root.withdraw()  # Hide the root window
    files = filedialog.askopenfilenames(title="Select Files to Zip")
    return files

def create_protected_zip(zip_name, files, password):
    """Create a password-protected ZIP file."""
    if not files:
        print("No files selected.")
        return
    
    zip_path = f"{zip_name}.zip"
    
    with pyzipper.AESZipFile(zip_path, 'w', compression=zipfile.ZIP_DEFLATED, encryption=pyzipper.WZ_AES) as zf:
        zf.setpassword(password.encode())  # Convert password to bytes
        for file in files:
            zf.write(file, os.path.basename(file))  # Add files to zip
            print(f"Added: {file}")

    print(f"Password-protected zip created: {zip_path}")

# Usage
selected_files = select_files()
if selected_files:
    create_protected_zip("secured_files", selected_files, "mypassword123")  # Change password as needed
