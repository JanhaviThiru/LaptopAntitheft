import psutil
import platform
import subprocess

def get_system_info():
    # Platform Information
    print("System Information:")
    print(f"System: {platform.system()}")
    print(f"Node Name: {platform.node()}")
    print(f"Release: {platform.release()}")
    print(f"Version: {platform.version()}")
    print(f"Machine: {platform.machine()}")
    print(f"Processor: {platform.processor()}")
    print("-" * 40)

    # CPU Information
    print("CPU Information:")
    print(f"CPU Cores (Physical): {psutil.cpu_count(logical=False)}")
    print(f"CPU Cores (Logical): {psutil.cpu_count(logical=True)}")
    print(f"CPU Frequency: {psutil.cpu_freq().current} MHz")
    
    # CPU Model (Alternative to cpuinfo)
    if platform.system() == "Windows":
        cpu_info = subprocess.check_output("wmic cpu get caption", shell=True).decode().strip()
        print(f"CPU Model: {cpu_info}")
    else:
        print("CPU Model: Not available for this platform.")
    
    print("-" * 40)

    # Memory Information
    print("Memory Information:")
    memory = psutil.virtual_memory()
    print(f"Total Memory: {memory.total / (1024**3):.2f} GB")
    print(f"Available Memory: {memory.available / (1024**3):.2f} GB")
    print(f"Used Memory: {memory.used / (1024**3):.2f} GB")
    print(f"Memory Usage: {memory.percent}%")
    print("-" * 40)

    # Disk Information
    print("Disk Information:")
    disk = psutil.disk_usage('/')
    print(f"Total Disk Space: {disk.total / (1024**3):.2f} GB")
    print(f"Used Disk Space: {disk.used / (1024**3):.2f} GB")
    print(f"Free Disk Space: {disk.free / (1024**3):.2f} GB")
    print(f"Disk Usage: {disk.percent}%")
    print("-" * 40)

    # Linux-specific information (if running on Linux)
    if platform.system() == "Linux":
        print("Linux Specific Information:")
        cpu_info_linux = subprocess.check_output("lscpu", shell=True)
        print(cpu_info_linux.decode())
        
        mem_info_linux = subprocess.check_output("free -h", shell=True)
        print(mem_info_linux.decode())
    
    # Windows-specific information (if running on Windows)
    elif platform.system() == "Windows":
        print("Windows Specific Information:")
        cpu_info_windows = subprocess.check_output("wmic cpu get caption, deviceid, numberofcores, maxclockspeed, status", shell=True)
        print(cpu_info_windows.decode())
        
        ram_info_windows = subprocess.check_output("wmic memorychip get capacity, devicelocator, manufacturer", shell=True)
        print(ram_info_windows.decode())
    
    # macOS-specific information (if running on macOS)
    elif platform.system() == "Darwin":
        print("macOS Specific Information:")
        cpu_info_macos = subprocess.check_output("sysctl -n machdep.cpu.brand_string", shell=True)
        print(cpu_info_macos.decode())
        
        mem_info_macos = subprocess.check_output("sysctl hw.memsize", shell=True)
        print(mem_info_macos.decode())

if __name__ == "__main__":
    get_system_info()
