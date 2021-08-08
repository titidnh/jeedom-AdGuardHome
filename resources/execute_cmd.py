#!/usr/bin/python

import sys, asyncio
from adguardhome import AdGuardHome

async def main(argv):
    try:
       async with AdGuardHome(argv[0], port=argv[1], username=argv[2], password=argv[3]) as adguard:
          if(argv[4] == 1)
          {
            await adguard.enable_protection()
            print("Done")
          }
          else{
            await adguard.disable_protection()
            print("Done")
          }
    except:
       print("Error")

if __name__ == "__main__":
    loop = asyncio.get_event_loop()
    loop.run_until_complete(main(sys.argv[1:]))