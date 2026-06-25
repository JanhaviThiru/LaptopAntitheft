import cv2
import time
import os
import json
import requests
from datetime import datetime
from configparser import ConfigParser

# Load configuration
config = ConfigParser()
config.read('set.ini')

    # Read values from configuration file
c_mail = config.get('section_server', 'name')
c_pass = config.get('section_server', 'pass')
ip_address = config.get('section_server', 'ip_address')
sip_address = config.get('section_server', 'sip_address')
pc_name = config.get('section_server', 'pc_name')
cl_id = config.get('section_server', 'cl_id')

# Folder to store captured images
local_folder_path = r'C:\xampp\htdocs\laptop_anti\captureding_' + pc_name +cl_id

def set_credentials():
    """Send credentials to the server and check if the camera is enabled."""
    data = {'fname': c_mail, 'fpass': c_pass, 'fpc_name': pc_name}

    try:
        response = requests.post(f'http://{sip_address}/laptop2/camera.php', data=data)
        response_json = response.json()  # Convert response to JSON

        print("Server Response:", response_json)  # Debugging step

        if response_json.get("status") == 1:
            print("Camera enabled, capturing images...")
            capture()
        else:
            print("Camera not enabled or invalid credentials.")

    except json.JSONDecodeError:
        print("Invalid JSON response from server:", response.text)
    except requests.RequestException as e:
        print(f"Error sending data: {e}")

def capture():
    """Capture images and upload their paths."""
    if not os.path.exists(local_folder_path):
        os.makedirs(local_folder_path)

    camera = cv2.VideoCapture(0)

    if not camera.isOpened():
        print("Error: Could not access the camera.")
        return

    image_paths = []
    for i in range(5):
        ret, frame = camera.read()
        if not ret:
            print("Error: Could not capture frame.")
            break

        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        image_filename = os.path.join(local_folder_path, f"image_{timestamp}_{i + 1}.jpg")
        cv2.imwrite(image_filename, frame)
        print(f"Image {i + 1} saved as {image_filename}")
        image_paths.append(image_filename)
        time.sleep(1)

    camera.release()
    cv2.destroyAllWindows()
    upload_image()

def upload_image():
    """Upload image paths to the server."""
    path = f'http://{ip_address}/laptop_anti/captureding'
    data = {'fpath': path, 'fpc_name': pc_name}

    try:
        response = requests.post(f'http://{sip_address}/laptop1/camera.php', data=data)
        print(response.text)
    except requests.RequestException as e:
        print(f"Error uploading image path: {e}")

if __name__ == "__main__":
    set_credentials()
