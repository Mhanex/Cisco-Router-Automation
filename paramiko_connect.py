import paramiko
import time
import os
from dotenv import load_dotenv
from datetime import datetime

#load environment variables
load_dotenv()

router1_host = os.getenv("ROUTER1_HOST")
port = int(os.getenv("CONN_PORT"))
router1_user = os.getenv("ROUTER1_USER")
router1_pass = os.getenv("ROUTER1_PASS")

ssh_client = paramiko.SSHClient()
ssh_client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    print(f"connecting to [{router1_host}:{port}]")
    ssh_client.connect(router1_host, port=port, username=router1_user, password=router1_pass,
                       look_for_keys=False, allow_agent=False)
    remote_conn = ssh_client.invoke_shell()
    time.sleep(1)

    remote_conn.send("enable\n")
    time.sleep(1)
    remote_conn.send(router1_pass + "\n")
    time.sleep(1)

    commands = [
        "terminal length 0",
        "configure terminal",
        "username temitope privilege 15 secret userpassword",
        "line vty 0 4",
        "exec-timeout 10 0",
        "exit",
        "banner login #Authorised Access Only#",
        "banner motd # Welcome to MTCyberX Router - SSH Access Only#",
        'exit',
        "show running-config",

    ]

    for cmd in commands:
        remote_conn.send(cmd + "\n")
        time.sleep(1)

    time.sleep(3)
    output = remote_conn.recv(65535).decode("utf-8")
    print(output)

    # ---- Save config output to file Optional---
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    filename = f"{router1_host}_{timestamp}.log"
    with open(filename, 'w', encoding='utf-8') as f:
        f.write(output)
        print(f"configuration saved to {filename}")


finally:
    if ssh_client.get_transport() and ssh_client.get_transport().is_active():
        print("Connection Closed")
        ssh_client.close()






