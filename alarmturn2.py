import requests
import os
from configparser import ConfigParser
import pygame
import time
config = ConfigParser()

config.read('set.ini')
c_mail = config.get('section_server', 'name')
c_pass = config.get('section_server', 'pass')
sip_address = config.get('section_server', 'sip_address')

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
            
            response = requests.post(f'http://{sip_address}/laptop1/alarm.php', data=data)
            print(response.text)

            # Check if the credentials are correct
            if "Welcome" in response.text:
                print("Success: Credentials verified!")
                
                # Check if scream is set to 1 in the response
                if "1" in response.text:
                    play_alarm_sound()
                    # After playing the alarm, update scream to 2
                  
            else:
                print("Error: Invalid credentials. Try again.")
        except requests.RequestException as e:
            print(f"Error sending data: {e}")
    else:
        print("Error: Please enter both Gmail ID and password.")

# Function to play the alarm sound
def play_alarm_sound():
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


set_credentials()
