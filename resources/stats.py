#!/usr/bin/python

import sys, getopt, asyncio
from adguardhome import AdGuardHome

async def main(argv):
    try:
       async with AdGuardHome(argv[0], port=argv[1], username=argv[2], password=argv[3]) as adguard:
          protectionActive = await adguard.protection_enabled()
          filteringActive = await adguard.filtering.enabled()
          parentalActive = await adguard.parental.enabled()
          safeBrowsingActive = await adguard.safebrowsing.enabled()
          safeSearchActive = await adguard.safesearch.enabled()
          processingTime = await adguard.stats.avg_processing_time()
          dnsQueries = await adguard.stats.dns_queries()
          nbrBlockedFiltering = await adguard.stats.blocked_filtering()
          nbrBlockedPercentage = await adguard.stats.blocked_percentage()
          nbrSafeBrowsing = await adguard.stats.replaced_safebrowsing()
          nbrParental = await adguard.stats.replaced_parental()
          nbrSafeSearch = await adguard.stats.replaced_safesearch()
          protectionActive = "1" if protectionActive else "0"
          filteringActive = "1" if filteringActive else "0"
          parentalActive = "1" if parentalActive else "0"
          safeBrowsingActive = "1" if safeBrowsingActive else "0"
          safeSearchActive = "1" if safeSearchActive else "0"
          print(protectionActive + ";" + filteringActive + ";" + parentalActive + ";" + safeBrowsingActive + ";" + safeSearchActive + ";" + str(processingTime) + ";" + str(dnsQueries) + ";" + str(nbrBlockedFiltering) + ";" + str(nbrBlockedPercentage) + ";" + str(nbrSafeBrowsing) + ";" + str(nbrParental) + ";" + str(nbrSafeSearch))
    except:
       print("Error")

if __name__ == "__main__":
    loop = asyncio.get_event_loop()
    loop.run_until_complete(main(sys.argv[1:]))