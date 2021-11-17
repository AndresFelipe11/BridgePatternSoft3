/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package remotes;

import devices.Device;

/**
 * @version 1.0
 * @author Cristian Guerrero - Andres Cadavid
 * method that inherits from the BasicRemote class.
 */
public class AdvancedRemote extends BasicRemote {

    public AdvancedRemote(Device device) {
        super.device = device;
    }

    public void mute() {
        System.out.println("Control remoto: botón sin sonido");
        device.setVolume(0);
    }
}