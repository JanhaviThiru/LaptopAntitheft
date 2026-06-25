import tkinter as tk
from tkinter import messagebox
import requests
import os
import uuid
import socket
import platform
import psutil  # Install psutil using pip
import time

try:
    from configparser import ConfigParser
except ImportError:
    from ConfigParser import ConfigParser  # For Python < 3.0

# Get the computer's name (PC Name)
pc_name = os.environ['COMPUTERNAME']

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

# Function to get additional system details
def get_system_details():
    system_details = {
        'system': platform.system(),
        'node_name': platform.node(),
        'release_version': platform.release(),
        'machine': platform.machine(),
        'processor': platform.processor(),
        'cpu_cores_physical': psutil.cpu_count(logical=False),
        'cpu_cores_logical': psutil.cpu_count(logical=True),
        'cpu_frequency': psutil.cpu_freq().current,
        'total_memory': psutil.virtual_memory().total,
        'available_memory': psutil.virtual_memory().available,
        'used_memory': psutil.virtual_memory().used,
        'memory_usage': psutil.virtual_memory().percent,
        'total_disk': psutil.disk_usage('/').total,
        'used_disk': psutil.disk_usage('/').used,
        'free_disk': psutil.disk_usage('/').free,
        'disk_usage': psutil.disk_usage('/').percent,
        'last_updated': time.strftime('%Y-%m-%d %H:%M:%S')
    }
    return system_details

def set_credentials():
    gmail_id = gmail_id_entry.get()
    password = password_entry.get()
    ip_address = get_ip_address()
    mac_address = get_mac_address()
    sip_address_value = sip_address_entry.get()

    if gmail_id and password:
        # Store the credentials in variables
        global stored_gmail_id, stored_password
        stored_gmail_id = gmail_id
        stored_password = password

        # Get the system details
        system_details = get_system_details()

        # Save credentials and system info to the .ini file
        save_credentials_to_ini(stored_gmail_id, stored_password, ip_address, mac_address, sip_address_value, system_details)

        # Call sendinfo to send data to the server and retrieve `c_id` and `cl_id`
        sendinfo(ip_address, mac_address, sip_address_value, system_details)
    else:
        messagebox.showwarning("Input Error", "Please enter both Gmail ID and Password")

def save_credentials_to_ini(gmail_id, password, ip_address, mac_address, sip_address, system_details, c_id=None, cl_id=None):
    # Create a ConfigParser instance
    config = ConfigParser()

    # Add a section and set the details
    config.add_section('section_server')
    config.set('section_server', 'name', gmail_id)
    config.set('section_server', 'pass', password)
    config.set('section_server', 'ip_address', ip_address)
    config.set('section_server', 'mac_address', mac_address)
    config.set('section_server', 'sip_address', sip_address)
    config.set('section_server', 'pc_name', pc_name)
    
    # Add system details
    for key, value in system_details.items():
        config.set('section_server', key, str(value))

    # If `c_id` and `cl_id` are provided, add them to the .ini file
    if c_id and cl_id:
        config.set('section_server', 'c_id', str(c_id))
        config.set('section_server', 'cl_id', str(cl_id))

    # Save the details to the .ini file
    with open('set.ini', 'w') as configfile:
        config.write(configfile)

def sendinfo(ip_address, mac_address, sip_address, system_details):
    try:
        # Sanitize the SIP address to remove extra spaces
        sanitized_sip_address = sip_address.strip()

        # Data to be sent to the PHP server, including system details
        data = {
            'fname': stored_gmail_id,
            'fpass': stored_password,
            'fpc_name': pc_name,
            'fip_address': ip_address,
            'fmac_address': mac_address,
            'fsystem': system_details['system'],
            'fnode_name': system_details['node_name'],
            'frelease_version': system_details['release_version'],
            'fmachine': system_details['machine'],
            'fprocessor': system_details['processor'],
            'fcpu_cores_physical': system_details['cpu_cores_physical'],
            'fcpu_cores_logical': system_details['cpu_cores_logical'],
            'fcpu_frequency': system_details['cpu_frequency'],
            'ftotal_memory': system_details['total_memory'],
            'favailable_memory': system_details['available_memory'],
            'fused_memory': system_details['used_memory'],
            'fmemory_usage': system_details['memory_usage'],
            'ftotal_disk': system_details['total_disk'],
            'fused_disk': system_details['used_disk'],
            'ffree_disk': system_details['free_disk'],
            'fdisk_usage': system_details['disk_usage'],
            'flast_updated': system_details['last_updated']
        }

        # Send data to the PHP page
        response = requests.post(f'http://{sanitized_sip_address}/laptop2/post1.php', data=data)

        # Handle the response
        if response.status_code == 200:
            print(response.text)

            # Parse the server response for c_id and cl_id
            try:
                response_data = response.json()  # Ensure PHP returns JSON {"c_id": ..., "cl_id": ...}
                c_id = response_data.get('c_id')
                cl_id = response_data.get('cl_id')

                if c_id and cl_id:
                    # Save the credentials along with c_id and cl_id
                    save_credentials_to_ini(stored_gmail_id, stored_password, ip_address, mac_address, sanitized_sip_address, system_details, c_id, cl_id)
                    messagebox.showinfo("Registration Successful", f"Details saved successfully!\nc_id: {c_id}, cl_id: {cl_id}")
                else:
                    messagebox.showwarning("Server Error", "Failed to retrieve c_id and cl_id from the server.")
            except ValueError as e:
                print(f"Error parsing server response: {e}")
                messagebox.showwarning("Error", "Invalid server response format.")
        else:
            print(f"Failed to send data, Status Code: {response.status_code}")
            messagebox.showwarning("Error", "Failed to communicate with the server.")
    except requests.RequestException as e:
        print(f"Error sending data: {e}")
        messagebox.showwarning("Error", f"Error sending data: {e}")

# Create the main window
root = tk.Tk()
root.title("Gmail Credentials")

# Create and place the labels and entry fields
tk.Label(root, text="SIP Address:").grid(row=0, column=0, padx=10, pady=10)
sip_address_entry = tk.Entry(root)
sip_address_entry.grid(row=0, column=1, padx=10, pady=10)

tk.Label(root, text="Gmail ID:").grid(row=1, column=0, padx=10, pady=10)
gmail_id_entry = tk.Entry(root)
gmail_id_entry.grid(row=1, column=1, padx=10, pady=10)

tk.Label(root, text="Password:").grid(row=2, column=0, padx=10, pady=10)
password_entry = tk.Entry(root, show="*")
password_entry.grid(row=2, column=1, padx=10, pady=10)

# Create and place the submit button
submit_button = tk.Button(root, text="Submit", command=set_credentials)
submit_button.grid(row=3, column=0, columnspan=2, pady=10)

# Run the application
root.mainloop()
