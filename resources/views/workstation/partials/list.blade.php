 <table class="table table-bordered">
    <thead>
           <tr rowspan=2>
               <td colspan=3>
                   {{ _('Workstation Name') }}:
                   <span style="font-weight: normal;">{{ $workstation->name }}</span>
               </td>
               <td colspan=3>
                   {{ _('License Key') }}
                   <span style="font-weight: normal;">{{ $workstation->oskey }}</span>
               </td>
           </tr>
   
           <tr rowspan=2>
               <td colspan=3>
                   {{ _('System Unit') }}:
                   <span style="font-weight: normal;">{{ $workstation->system_unit_local }}</span>
               </td>
               <td colspan=3>
                   {{ _('Monitor') }}
                   <span style="font-weight: normal;">{{ $workstation->monitor_local }}</span>
               </td>
           </tr>
   
           <tr rowspan=2>
               <td colspan=3>
                   {{ _('AVR') }}:
                   <span style="font-weight: normal;">{{ $workstation->avr_local }}</span>
               </td>
               <td colspan=3>
                   {{ _('Keyboard') }}
                   <span style="font-weight: normal;">{{ $workstation->keyboard_local }}</span>
               </td>
           </tr>
   
           <tr rowspan=2>
               <td colspan=3>
                   {{ _('Mouse') }}:
                   <span style="font-weight: normal;">{{ $workstation->mouse_local }}</span>
               </td>
               <td colspan=3>
                   {{ _('Location') }}
                   <span style="font-weight: normal;">{{ $workstation->location }}</span>
               </td>
           </tr>
   
           <tr rowspan=2>
               <td colspan=3>
                   {{ _('Tickets Issued') }}:
                   <span style="font-weight: normal;"></span>
               </td>
               <td colspan=3>
                   {{ _('Mouse Issued') }}
                   <span style="font-weight: normal;"></span>
               </td>
           </tr>
       </thead>
 </table>