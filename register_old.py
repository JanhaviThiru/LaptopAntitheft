import tkinter as tk
from tkinter import messagebox
import requests
import os
import uuid
import socket

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

        # Save credentials and system info to the .ini file
        save_credentials_to_ini(stored_gmail_id, stored_password, ip_address, mac_address, sip_address_value)

        # Call sendinfo to send data to the server and retrieve `c_id` and `cl_id`
        sendinfo(ip_address, mac_address, sip_address_value)
    else:
        messagebox.showwarning("Input Error", "Please enter both Gmail ID and Password")

def save_credentials_to_ini(gmail_id, password, ip_address, mac_address, sip_address, c_id=None, cl_id=None):
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
    
    # If `c_id` and `cl_id` are provided, add them to the .ini file
    if c_id and cl_id:
        config.set('section_server', 'c_id', str(c_id))
        config.set('section_server', 'cl_id', str(cl_id))

    # Save the details to the .ini file
    with open('set.ini', 'w') as configfile:
        config.write(configfile)

def sendinfo(ip_address, mac_address, sip_address):
    try:
        # Sanitize the SIP address to remove extra spaces
        sanitized_sip_address = sip_address.strip()

        # Data to be sent to the PHP server
        data = {
            'fname': stored_gmail_id,
            'fpass': stored_password,
            'fpc_name': pc_name,
            'fip_address': ip_address,
            'fmac_address': mac_address
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
                    save_credentials_to_ini(stored_gmail_id, stored_password, ip_address, mac_address, sanitized_sip_address, c_id, cl_id)
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

# Run the applicationabc@123
root.mainloop()
