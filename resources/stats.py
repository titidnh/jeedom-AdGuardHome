#!/usr/bin/python

import sys, asyncio
from adguardhome import AdGuardHome

async def main(argv):
    try:
       async with AdGuardHome(argv[0], port=argv[1], username=argv[2], password=argv[3]) as adguard:
          protectionActive = await adguard.protection_enabled()
          processingTime = await adguard.stats.avg_processing_time()
          dnsQueries = await adguard.stats.dns_queries()
          nbrBlockedFiltering = await adguard.stats.blocked_filtering()
          nbrBlockedPercentage = await adguard.stats.blocked_percentage()
          protectionActive = "1" if protectionActive else "0"
          print(protectionActive + ";" + str(processingTime) + ";" + str(dnsQueries) + ";" + str(nbrBlockedFiltering) + ";" + str(nbrBlockedPercentage))
    except:
       print("Error")

if __name__ == "__main__":
    loop = asyncio.get_event_loop()
    loop.run_until_complete(main(sys.argv[1:]))