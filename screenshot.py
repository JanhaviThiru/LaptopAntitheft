import pyautogui
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

# Folder to store captured screenshots
local_folder_path = r'C:\xampp\htdocs\laptop_anti\screenshot' + pc_name + cl_id

def set_credentials():
    """Send credentials to the server and check if screenshots are enabled."""
    data = {'fname': c_mail, 'fpass': c_pass, 'fpc_name': pc_name}

    try:
        response = requests.post(f'http://{sip_address}/laptop2/screenshot.php', data=data)
        response_json = response.json()  # Convert response to JSON

        print("Server Response:", response_json)  # Debugging step

        if response_json.get("status") == 1:
            print("Screenshot capturing enabled, taking screenshots...")
            capture_screenshots()
        else:
            print("Screenshot capturing not enabled or invalid credentials.")

    except json.JSONDecodeError:
        print("Invalid JSON response from server:", response.text)
    except requests.RequestException as e:
        print(f"Error sending data: {e}")

def capture_screenshots():
    """Capture screenshots and upload their paths."""
    if not os.path.exists(local_folder_path):
        os.makedirs(local_folder_path)

    screenshot_paths = []
    for i in range(5):
        # Capture a screenshot
        screenshot = pyautogui.screenshot()
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        screenshot_filename = os.path.join(local_folder_path, f"screenshot_{timestamp}_{i + 1}.png")
        screenshot.save(screenshot_filename)
        print(f"Screenshot {i + 1} saved as {screenshot_filename}")
        screenshot_paths.append(screenshot_filename)
        time.sleep(1)  # Delay between screenshots

    upload_screenshot_paths(screenshot_paths)

def upload_screenshot_paths(screenshot_paths):
    """Upload screenshot paths to the server."""
    for screenshot_path in screenshot_paths:
        data = {'fpath': screenshot_path, 'fpc_name': pc_name}

        try:
            response = requests.post(f'http://{sip_address}/laptop2/screenshot.php', data=data)
            print(response.text)
        except requests.RequestException as e:
            print(f"Error uploading screenshot path: {e}")

if __name__ == "__main__":
    set_credentials()
