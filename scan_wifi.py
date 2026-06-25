from pywifi import PyWiFi
import time

def get_wifi_networks():
    wifi = PyWiFi()
    iface = wifi.interfaces()[0]  # Get the first wireless interface
    print(f"Scanning on interface: {iface.name()}")
    
    iface.scan()  # Start scanning
    time.sleep(5)  # Wait for a few seconds to let the scan complete
    results = iface.scan_results()  # Get scan results
    
    if not results:
        print("No networks found. Please check your Wi-Fi interface.")
        return []
    
    networks = []
    for network in results:
        ssid = network.ssid  # SSID of the network
        mac = network.bssid  # MAC address of the network
        networks.append({"SSID": ssid, "MAC Address": mac})

    return networks

networks = get_wifi_networks()
if networks:
    for network in networks:
        print(f"SSID: {network['SSID']}, MAC Address: {network['MAC Address']}")
else:
    print("No Wi-Fi networks found.")


##from pywifi import PyWiFi
##import time
##
##def get_wifi_networks():
##    wifi = PyWiFi()
##    iface = wifi.interfaces()[0]  # Get the first wireless interface
##    print(f"Scanning on interface: {iface.name()}")
##    
##    iface.scan()  # Start scanning
##    time.sleep(5)  # Wait for a few seconds to let the scan complete
##    results = iface.scan_results()  # Get scan results
##    
##    if not results:
##        print("No networks found. Please check your Wi-Fi interface.")
##        return []
##
##    seen_ssids = set()  # Set to track already seen SSIDs
##    ssid_mac_pairs = []  # List to store SSID and MAC address pairs
##    
##    for network in results:
##        ssid = network.ssid  # SSID of the network
##        mac = network.bssid  # MAC address of the network
##        
##        # Skip empty SSID or incomplete MAC addresses
##        if not ssid or len(mac) != 17:
##            continue
##        
##        # Skip duplicate SSIDs
##        if ssid in seen_ssids:
##            continue
##        
##        # Add SSID to the seen set
##        seen_ssids.add(ssid)
##        
##        # Append SSID and MAC as a tuple
##        ssid_mac_pairs.append((ssid, mac))
##
##    return ssid_mac_pairs
##
### Get SSID and MAC pairs
##ssid_mac_pairs = get_wifi_networks()
##
### Print SSID and MAC address pairs
##if ssid_mac_pairs:
##    for ssid, mac in ssid_mac_pairs:
##        print(f"SSID: {ssid}, MAC Address: {mac}")
##else:
##    print("No Wi-Fi networks found.")
##
