#import tkinter as tk
#from tkinter import messagebox
import requests
import os
import subprocess
import requests

try:
    from configparser import ConfigParser
except ImportError:
    from ConfigParser import ConfigParser  # For Python < 3.0

# instantiate
config = ConfigParser()

# parse existing file
config.read('set.ini')

# read values from a section
c_mail = config.get('section_server', 'name')
c_pass = config.get('section_server', 'pass')

# Variables for credentials
stored_gmail_id = None
stored_password = None
pc_name = os.environ['COMPUTERNAME']

def set_credentials():
    global stored_gmail_id, stored_password
    
    gmail_id = c_mail
    password = c_pass
    
    if gmail_id and password:
        stored_gmail_id = gmail_id
        stored_password = password

        try:
            data = {'fname': stored_gmail_id, 'fpass': stored_password, 'fpc_name': pc_name}
            response = requests.post('http://localhost/laptop2/post.php', data=data)
            print(response.text)

            # Check if the credentials are correct
            if "Welcome" in response.text:
                print("Success", "Credentials verified!")
                
                # Check if locate is set to 1 in the response and call send_location
                if "1" in response.text:
                    send_location()
                
                    
                   
            else:
                print("Error", "Invalid credentials. Try again.")
        except requests.RequestException as e:
            print(f"Error sending data: {e}")
            print("Error", f"Error sending data: {e}")
    else:
        print("Error", "Please enter both Gmail ID and password.")

def get_location():
    try:
        response = requests.get('http://ipinfo.io')
        data = response.json()
        location = data['loc'].split(',')
        latitude = location[0]
        longitude = location[1]
        return latitude, longitude
    except requests.RequestException as e:
        print("Error fetching location:", e)
        return None, None

def send_location():
    latitude, longitude = get_location()
    
    if latitude and longitude:
        try:
            data = {
                'fname': stored_gmail_id,
                'fpass': stored_password,
                'fpc_name': pc_name,
                'latitude': latitude,
                'longitude': longitude
            }
            response = requests.post('http://localhost/laptop1/post.php', data=data)
            response.raise_for_status()

            data1 = response.text
            if "Location updated successfully!" in data1:
                print("Post response:", data1)
            elif "Locate has already been updated" in data1:
                print("Post response:", data1)
            else:
                print("Error:", data1)
        except requests.RequestException as e:
            print(f"Error sending location: {e}")
    else:
        print("Could not get location data.")

set_credentials()