import paramiko
import os
import time
from dotenv import load_dotenv

#load router environment variables
load_dotenv()

routers = [
    {
        "host": os.getenv("ROUTER1_HOST"),
        "port": int(os.getenv("CONN_PORT")),
        "user": os.getenv("ROUTER1_USER"),
        "pass": os.getenv("ROUTER1_PASS")
    },
    {
        "host": os.getenv("ROUTER2_HOST"),
        "port": int(os.getenv("CONN_PORT")),
        "user": os.getenv("ROUTER2_USER"),
        "pass": os.getenv("ROUTER2_PASS")
    }

]

#call paramiko
def automateRouter(router):
    ssh_client = paramiko.SSHClient()
    ssh_client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

    try:
        print(f'Connecting to {router["host"]}:{router["port"]}')
        ssh_client.connect(router['host'], port=router['port'],
                           username=router['user'],
                           password=router['pass'],
                           look_for_keys=False, allow_agent=False)

        remote_conn = ssh_client.invoke_shell()
        time.sleep(1)


        commands = [
            "terminal length 0",
            "configure terminal",
            "username tope privilege 5 secret pass1",
            "privilege exec level 5 show ip route",
            "privilege exec level 5 show ip int brief",
            "line console 0",
            "login local",
            "end",
            "copy running-config startup-config"
        ]

        for cmd in commands:
            remote_conn.send(cmd + "\n")
            time.sleep(1)

        output = remote_conn.recv(65535).decode("utf-8")
        print(output)

    finally:
        if ssh_client.get_transport() and ssh_client.get_transport().is_active():
            print("Closing SSH connection")
            ssh_client.close()

for router in routers:
    automateRouter(router)

