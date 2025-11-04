<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Enums;

enum AccountNamespace: string
{
    case System = '';
    case Organization = 'o';
    case OrganizationScores = 'osr';
    case Custom = 'cm';
    case Scores = 'sc';
    case AutopilotJourneys = 'a2';
    case BigCommerce = 'bc';
    case Calendly = 'cl';
    case Chargebee = 'cb';
    case Facebook = 'fb';
    case Google = 'gl';
    case Gorgias = 'gg';
    case HelpScout = 'hs';
    case LinkedIn = 'ln';
    case Magento = 'mg';
    case Pipedrive = 'pi';
    case PrestaShop = 'ps';
    case Recurly = 'r';
    case SalesforceAccount = 'osa';
    case SalesforceAccountCustom = 'osb';
    case SalesforceCampaign = 'sfk';
    case SalesforceCampaignCustom = 'skc';
    case SalesforceContact = 'sfc';
    case SalesforceContactCustom = 'scc';
    case SalesforceLead = 'sfl';
    case SalesforceLeadCustom = 'slc';
    case SalesforceCustomObject = 'soc';
    case SalesforceObject = 'sfo';
    case SalesforceOpportunity = 'sfp';
    case SalesforceOpportunityCustom = 'spc';
    case SalesforceOrganization = 'oso';
    case SalesforceOrganizationCustom = 'osc';
    case SalesforceTask = 'sft';
    case SalesforceTaskCustom = 'stc';
    case Segment = 'sm';
    case Shopify = 'sh';
    case Slack = 'sk';
    case SmsForm = 'sf';
    case Stripe = 'st';
    case Swell = 'sw';
    case Twitter = 'tw';
    case Typeform = 'tf';
    case WidgetForm = 'wf';
    case WooCommerce = 'wc';
    case Zendesk = 'zd';
}
