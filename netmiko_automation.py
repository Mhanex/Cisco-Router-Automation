# from netmiko import Netmiko

# conn = Netmiko(device_type="cisco_ios",host="192.168.20.100",username="temitope",port="22",password="password")

from netmiko import ConnectHandler
Router1 = {
    "device_type": "cisco_ios",
    "host": "192.168.20.100",
    "username": "temitope", #SSH User name
    "password": "password", #SSh User password
    "port": 22,
    "secret": "test123", #router password
    "verbose": True,
}
print(f"Connecting to {Router1['host']}")
conn = ConnectHandler(**Router1)
prompt = conn.find_prompt()
if '>' in prompt:
    conn.enable()
if not conn.check_config_mode():
    conn.config_mode()

commands = [
    "int f2/0",
    "ip address 192.168.1.11 255.255.255.0",
    "no shut",
    "end",
    "sh ip interface brief"
]
output = conn.send_config_set(commands)
print(output)
conn.send_command("write memory")

# for Single command
# for cmd in commands:
#     output = conn.send_command(cmd)
#     print(output)

print("Closing connection")
conn.disconnect()