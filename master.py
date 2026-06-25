import pyautogui
import pygame
import cv2
import time
import os
import json
import requests
import tkinter as tk
from tkinter import messagebox  # Import messagebox directly
from datetime import datetime
from configparser import ConfigParser
import shutil
from tkinter.filedialog import askopenfilenames
import googlemaps
from googlemaps import exceptions
from pywifi import PyWiFi
import configparser

# Load configuration
config = ConfigParser()
config.read('set.ini')
CONFIG_FILE = "set.ini"

try:
    # Read values from configuration file
    c_mail = config.get('section_server', 'name')
    c_pass = config.get('section_server', 'pass')
    ip_address = config.get('section_server', 'ip_address')
    sip_address = config.get('section_server', 'sip_address')
    pc_name = config.get('section_server', 'pc_name')
    c_id = config.get('section_server', 'c_id')
    cl_id = config.get('section_server', 'cl_id')
except Exception as e:
    print(f"Error loading configuration: {e}")
    exit()

# Folder paths
camera_folder_path = "C:/Users/Public/Pictures/images"
screenshot_folder_path = "C:/Users/Public/Pictures/screenshots"
server_url = f'http://{sip_address}/laptop2/upload.php'

 
def get_location():
    _GEOLOCATION_BASE_URL = "https://www.googleapis.com"

    def get_wifi_networks():
        wifi = PyWiFi()
        iface = wifi.interfaces()[0]  # Get the first wireless interface
        iface.scan()  # Start scanning
        time.sleep(5)  # Wait for scan to complete
        results = iface.scan_results()  # Get scan results
        
        networks = []
        for network in results:
            networks.append({
                "macAddress": network.bssid,  # MAC address of the network
                "signalStrength": network.signal,  # Signal strength
                "signalToNoiseRatio": 0  # Set SNR to 0 (optional)
            })
        return networks

    def _geolocation_extract(response):
        body = response.json()
        if response.status_code in (200, 404):
            return body
        
        try:
            error = body["error"]["errors"][0]["reason"]
        except KeyError:
            error = None
        
        if response.status_code == 403:
            raise exceptions._OverQueryLimit(response.status_code, error)
        else:
            raise exceptions.ApiError(response.status_code, error)

    def geolocate(client, wifi_access_points=None, consider_ip=False):
        params = {"considerIp": str(consider_ip).lower()}
        if wifi_access_points:
            params["wifiAccessPoints"] = wifi_access_points
        
        return client._request("/geolocation/v1/geolocate", {},  # No GET params
                               base_url=_GEOLOCATION_BASE_URL,
                               extract_body=_geolocation_extract,
                               post_json=params)

    api_key = 'AIzaSyD_Zoeh9_x_Qm8bIDGrKiWN-K8_2lPGzgQ'  # Replace with your API key
    gmaps = googlemaps.Client(key=api_key)
    
    wifi_networks = get_wifi_networks()
    if not wifi_networks:
        print("No Wi-Fi networks found. Unable to perform geolocation.")
        return None, None

    result = geolocate(gmaps, wifi_access_points=wifi_networks)
    
    if 'location' in result:
        latitude = result['location']['lat']
        longitude = result['location']['lng']
        print(f"Latitude: {latitude}, Longitude: {longitude}")  # Debugging statement
        return latitude, longitude
    else:
        print("Failed to get geolocation data.")
        return None, None


    
def send_location():
            latitude, longitude = get_location()
            
            if latitude and longitude:
                try:
                    data = {
                        'fname': c_mail,
                        'fpass': c_pass,
                        'fpc_name': pc_name,
                        'latitude': latitude,
                        'longitude': longitude
                    }
                    response = requests.post(f'http://{sip_address}/laptop2/post.php', data=data)
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
       
 

    
def scream():
        try:
            pygame.mixer.init()
            sound_file = "alarm.mp3"  # Replace with your sound file path
            if not os.path.exists(sound_file):
                print("Error: Sound file not found.")
                return
    
            pygame.mixer.music.load(sound_file)
            pygame.mixer.music.play()
            print("Alarm ringing...")
    
            
            while pygame.mixer.music.get_busy():
                time.sleep(1)
        except Exception as e:
            print(f"Error playing sound: {e}")
    
def camera():
    """Capture images and send them to the server."""
    print("Executing: camera")

    cl_id_folder_path = os.path.join(camera_folder_path, str(c_id), str(cl_id))
    os.makedirs(cl_id_folder_path, exist_ok=True)  # Creates all missing parent directories


    camera = cv2.VideoCapture(0)
    if not camera.isOpened():
        print("Error: Could not access the camera.")
        return

    for i in range(5):
        ret, frame = camera.read()
        if not ret:
            print("Error: Could not capture frame.")
            break

        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        image_filename = os.path.join(cl_id_folder_path, f"image_{timestamp}_{i + 1}.jpg")
        cv2.imwrite(image_filename, frame)
        print(f"Saved: {image_filename}")
        
        # Send image to server
        upload_to_server(image_filename)

        time.sleep(5)  # Pause between captures

    camera.release()
    cv2.destroyAllWindows()

def upload_to_server(image_path):
    """Upload captured image to the server."""
    try:
        with open(image_path, "rb") as file:
            files = {"file": file}
            data = {"c_id": c_id, "cl_id": cl_id}  # Send c_id and cl_id
            response = requests.post(server_url, files=files, data=data)
            print(response.text)  # Server response
    except Exception as e:
        print("Error uploading image:", e)


def screenshot():
    """Capture screenshots, save them, and upload them automatically."""
    print("Executing: screenshot")
    
    cl_id_screenshot_path = os.path.join(screenshot_folder_path, str(c_id), str(cl_id))
    os.makedirs(cl_id_screenshot_path, exist_ok=True)  # Creates all missing parent directories


    for i in range(5):  # Capture 5 screenshots
        screenshot = pyautogui.screenshot()
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        screenshot_filename = os.path.join(cl_id_screenshot_path, f"screenshot_{timestamp}_{i + 1}.png")
        screenshot.save(screenshot_filename)
        print(f"Saved: {screenshot_filename}")
        
        # Upload screenshot after saving
        upload_screenshot(screenshot_filename)

        time.sleep(5)  # Wait before capturing the next one

def upload_screenshot(screenshot_path):
    """Upload a screenshot to the server."""
    try:
        with open(screenshot_path, "rb") as file:
            files = {"file": file}
            data = {"c_id": c_id, "cl_id": cl_id}  # Send c_id and cl_id
            response = requests.post(server_url, files=files, data=data)
            print(response.text)  # Print server response
    except Exception as e:
        print("Error uploading screenshot:", e)
            
def datawipe():
    config = configparser.ConfigParser()

    if not os.path.exists(CONFIG_FILE):
        return  # Exit if config file is missing

    config.read(CONFIG_FILE)

    if "Security" not in config or "files" not in config["Security"]:
        return  # Exit if no files are listed

    # Use comma `,` instead of semicolon `;` as separator
    file_paths = config["Security"]["files"].split(",")

    for file in file_paths:
        file = file.strip()
        if os.path.exists(file):
            try:
                os.remove(file)
            except Exception:
                pass  # Ignore errors and continue
    
    # Map digits to functions
actions = {
        0: send_location,
        1: scream,
        2: camera,
        3: screenshot,
        4: datawipe
        
    }

data = {'fname': c_mail, 'fpass': c_pass, 'fpc_name': pc_name}
response = requests.post(f'http://{sip_address}/laptop2/master.php', data=data)
print(response.text)
    
    # Input string
x=response.text

print(x)

text= x.strip('[]').replace('"', '')  # Remove square brackets and quotes
arr = text.split(',')  # Split by comma

# Join the elements with a space
result = ' '.join(arr)
    
    # Execute actions based on digits
# Execute actions based on digits
for index, value in enumerate(arr):
    if value in '01234':  # Only handle valid indices (0, 1, 2, 3)
        if value == '1':
            action = actions.get(index)
            if action:
                action()
    else:
        print(f"Ignoring invalid character at index {index}: {value}")

# Display the warning message
x5 = arr[5]  # Showing the character at index 4
root = tk.Tk()
root.withdraw()  # Hide the main window
messagebox.showwarning("Warning", x5)



            
