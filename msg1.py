import requests
import os
import tkinter as tk
from tkinter import messagebox  # Import messagebox directly

try:
    from configparser import ConfigParser
except ImportError:
    from ConfigParser import ConfigParser  # For Python < 3.0

# Create a single Tk instance at the top level
root = tk.Tk()
root.withdraw()  # Hide the main window immediately

def get_credentials():
    # Instantiate and parse config
    config = ConfigParser()
    config.read('set.ini')
    return config.get('section_server', 'name'), config.get('section_server', 'pass')

def set_credentials(gmail_id, password):
    pc_name = os.environ['COMPUTERNAME']
    
    if gmail_id and password:
        try:
            data = {'fname': gmail_id, 'fpass': password, 'fpc_name': pc_name}
            response = requests.post('http://localhost/laptop1/post1.php', data=data)
            response_text = response.text.strip()
            print(response_text)

            # Check if credentials are correct and extract the message
            if "Welcome" in response_text:
                print("Success", "Credentials verified!")

                # Extract message from the response by splitting on ';'
                parts = response_text.split(';')
                for part in parts:
                    if "p_msg:" in part:
                        user_message = part.split("p_msg:")[1].strip()
                        show_popup(user_message)  # Show the popup with the message
                        break
            else:
                print("Error", "Invalid credentials. Try again.")
        except requests.RequestException as e:
            print(f"Error sending data: {e}")
    else:
        print("Error", "Please enter both Gmail ID and password.")

def show_popup(message):
    # Use the existing root instance to show the message
    messagebox.showwarning(title="Message", message=message)  # Show the popup with the message
    root.quit()  # Quit the Tk instance after showing the message

if __name__ == "__main__":
    gmail_id, password = get_credentials()
    set_credentials(gmail_id, password)
    root.destroy()  # Ensure the root instance is destroyed when done
