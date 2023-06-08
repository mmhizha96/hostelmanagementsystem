import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RoomsettingsComponent } from './roomsettings.component';

describe('RoomsettingsComponent', () => {
  let component: RoomsettingsComponent;
  let fixture: ComponentFixture<RoomsettingsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RoomsettingsComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RoomsettingsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
