const fs = require("fs");
const path = require("path");

const base = "C:/Users/work/Herd/vectos/packages/escalated-laravel/src";
const reqDir = path.join(base, "Http", "Requests");
const polDir = path.join(base, "Policies");
fs.mkdirSync(reqDir, { recursive: true });
fs.mkdirSync(polDir, { recursive: true });

const BS = String.fromCharCode(92);
const D = String.fromCharCode(36);
function ns(parts) { return parts.join(BS); }

const nsReq = ns(["Escalated", "Laravel", "Http", "Requests"]);
const nsFormRequest = ns(["Illuminate", "Foundation", "Http", "FormRequest"]);
const nsPol = ns(["Escalated", "Laravel", "Policies"]);
const nsTicket = ns(["Escalated", "Laravel", "Models", "Ticket"]);
const nsDepartment = ns(["Escalated", "Laravel", "Models", "Department"]);
const nsSlaPolicy = ns(["Escalated", "Laravel", "Models", "SlaPolicy"]);
const nsEscalationRule = ns(["Escalated", "Laravel", "Models", "EscalationRule"]);
const nsTag = ns(["Escalated", "Laravel", "Models", "Tag"]);
const nsCannedResponse = ns(["Escalated", "Laravel", "Models", "CannedResponse"]);
const nsHandles = ns(["Illuminate", "Auth", "Access", "HandlesAuthorization"]);
const nsGate = ns(["Illuminate", "Support", "Facades", "Gate"]);

// Load templates from JSON
const templates = JSON.parse(fs.readFileSync(path.join(base, "..", "_templates.json"), "utf8"));

let count = 0;
for (const [key, tmpl] of Object.entries(templates)) {
  const dir = tmpl.dir === "req" ? reqDir : polDir;
  const filePath = path.join(dir, key);
  let content = tmpl.content;
  // Replace placeholders
  content = content.replace(/{nsReq}/g, nsReq);
  content = content.replace(/{nsFormRequest}/g, nsFormRequest);
  content = content.replace(/{nsPol}/g, nsPol);
  content = content.replace(/{nsTicket}/g, nsTicket);
  content = content.replace(/{nsDepartment}/g, nsDepartment);
  content = content.replace(/{nsSlaPolicy}/g, nsSlaPolicy);
  content = content.replace(/{nsEscalationRule}/g, nsEscalationRule);
  content = content.replace(/{nsTag}/g, nsTag);
  content = content.replace(/{nsCannedResponse}/g, nsCannedResponse);
  content = content.replace(/{nsHandles}/g, nsHandles);
  content = content.replace(/{nsGate}/g, nsGate);
  content = content.replace(/{D}/g, D);
  fs.writeFileSync(filePath, content);
  console.log("Created: " + key);
  count++;
}
console.log("Total: " + count);